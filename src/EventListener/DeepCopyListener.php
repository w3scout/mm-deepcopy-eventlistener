<?php
// src/EventListener/DeepCopyListener.php

namespace App\EventListener;

use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\DcGeneral\Data\ModelIdInterface;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Event\PostDuplicateModelEvent;
use ContaoCommunityAlliance\DcGeneral\Event\PreDuplicateModelEvent;
use Terminal42\ServiceAnnotationBundle\Annotation\ServiceTag;

/**
 * @ServiceTag("kernel.event_listener", event=PostDuplicateModelEvent::NAME)
 */
class DeepCopyListener
{
    public function onDcGeneralModelPostDuplicate(PostDuplicateModelEvent $event)
    {
        #dump($event);
        $environment = $event->getEnvironment();

        $sourceModel = $event->getSourceModel();
        $sourceModelId = ModelId::fromModel($sourceModel);

        $newModel = $event->getModel();
        $newModel = $this->renameModel($environment, $sourceModel, $newModel);
        $newModelId = ModelId::fromModel($newModel);

        $this->deepCopy($environment, $sourceModelId, $newModelId);
    }

    private function deepCopy(EnvironmentInterface $environment, ModelIdInterface $sourceModelId, ModelIdInterface $newModelId)
    {
        $relationships = $environment->getDataDefinition()->getDefinition('model-relationships');
        $childConditions = $relationships->getChildConditions($sourceModelId->getDataProviderName());

        if(empty($childConditions)) return;

        foreach ($childConditions as $childCondition) {
            $dataProvider       = $environment->getDataProvider($sourceModelId->getDataProviderName());
            $model              = $dataProvider->fetch(
                $dataProvider->getEmptyConfig()->setId($sourceModelId->getId())
            );
            $childDataProvider  = $environment->getDataProvider($childCondition->getDestinationName());
            $filters            = $childCondition->getFilter($model);
            $childModels        = $childDataProvider->fetchAll($dataProvider->getEmptyConfig()->setFilter($filters));

            if ($childModels->count() < 1) {
                continue;
            }

            foreach ($childModels as $childModel) {
                $childModelId = ModelId::fromModel($childModel);
                $clonedChildModelId = $this->copy($environment, $childModelId, $newModelId);

                $this->deepCopy($environment, $childModelId, $clonedChildModelId);
            }
        }
    }

    private function copy(EnvironmentInterface $environment, ModelIdInterface $childModelId, ModelIdInterface $newModelId)
    {
        $dataProvider = $environment->getDataProvider($childModelId->getDataProviderName());
        $childModel   = $dataProvider->fetch($dataProvider->getEmptyConfig()->setId($childModelId->getId()));

        $newChildModel = $environment->getController()->createClonedModel($childModel);
        $newChildModel->setProperty('pid', $newModelId->getId());

        $eventDispatcher = $environment->getEventDispatcher();
        // Dispatch pre duplicate event.
        $preCopyEvent = new PreDuplicateModelEvent($environment, $newChildModel, $childModel);
        $eventDispatcher->dispatch($preCopyEvent, $preCopyEvent::NAME);

        // Save the copy.
        $environment->getDataProvider($newChildModel->getProviderName())->save($newChildModel);

        // Dispatch post duplicate event.
        $postCopyEvent = new PostDuplicateModelEvent($environment, $newChildModel, $childModel);
        $eventDispatcher->dispatch($postCopyEvent, $postCopyEvent::NAME);

        return ModelId::fromModel($newChildModel);
    }

    private function renameModel($environment, $sourceModel, $newModel)
    {
        if(null !== $sourceModel->getProperty('titel')) {
            $newModel->setProperty('titel', sprintf('%s (%s)', $sourceModel->getProperty('titel'), 'Kopie'));
            $environment->getDataProvider($newModel->getProviderName())->save($newModel);
        }

        return $newModel;
    }
}
