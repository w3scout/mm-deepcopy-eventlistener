<?php
// src/EventListener/DeepCopyListener.php

namespace App\EventListener;

use ContaoCommunityAlliance\DcGeneral\Data\DataProviderInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\Definition\ModelRelationshipDefinitionInterface;
use ContaoCommunityAlliance\DcGeneral\DataDefinition\ModelRelationship\ParentChildConditionInterface;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use ContaoCommunityAlliance\DcGeneral\Event\PostDuplicateModelEvent;
use ContaoCommunityAlliance\DcGeneral\Event\PreDuplicateModelEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Terminal42\ServiceAnnotationBundle\Annotation\ServiceTag;

/**
 * @ServiceTag("kernel.event_listener", event=PostDuplicateModelEvent::NAME)
 */
class DeepCopyListener
{
    /**
     * The deep copy listener.
     *
     * @param PostDuplicateModelEvent $event
     *
     * @return void
     */
    public function onDcGeneralModelPostDuplicate(PostDuplicateModelEvent $event): void
    {
        $environment = $event->getEnvironment();
        $sourceModel = $event->getSourceModel();

        $newModel = $event->getModel();
        $this->renameModel($environment, $sourceModel, $newModel);

        $this->deepCopy($environment, $sourceModel, $newModel);
    }

    /**
     * Generate copy of all child models.
     *
     * @param EnvironmentInterface $environment
     * @param ModelInterface       $sourceModel
     * @param ModelInterface       $newModel
     *
     * @return void
     */
    private function deepCopy(
        EnvironmentInterface $environment,
        ModelInterface $sourceModel,
        ModelInterface $newModel
    ): void {
        $relationships = $environment->getDataDefinition()->getDefinition('model-relationships');
        assert($relationships instanceof ModelRelationshipDefinitionInterface);
        $childConditions = $relationships->getChildConditions($sourceModel->getProviderName());

        if (empty($childConditions)) {
            return;
        }

        $dispatcher = $environment->getEventDispatcher();
        foreach ($childConditions as $childCondition) {
            $childProvider = $environment->getDataProvider($childCondition->getDestinationName());
            $filters       = $childCondition->getFilter($sourceModel);
            $childModels   = $childProvider->fetchAll($childProvider->getEmptyConfig()->setFilter($filters));

            if ($childModels->count() < 1) {
                continue;
            }

            foreach ($childModels as $childModel) {
                $clonedChildModel = $this->copy(
                    $environment,
                    $childProvider,
                    $childModel,
                    $newModel,
                    $childCondition,
                    $dispatcher
                );

                $this->deepCopy($environment, $childModel, $clonedChildModel);
            }
        }
    }

    /**
     * Copy model.
     *
     * @param EnvironmentInterface          $environment
     * @param DataProviderInterface         $childDataProvider
     * @param ModelInterface                $childModel
     * @param ModelInterface                $parentModel
     * @param ParentChildConditionInterface $condition
     * @param EventDispatcherInterface      $dispatcher
     *
     * @return ModelInterface
     */
    private function copy(
        EnvironmentInterface $environment,
        DataProviderInterface $childDataProvider,
        ModelInterface $childModel,
        ModelInterface $parentModel,
        ParentChildConditionInterface $condition,
        EventDispatcherInterface $dispatcher
    ): ModelInterface {
        $newChildModel = $environment->getController()->createClonedModel($childModel);
        $condition->applyTo($parentModel, $newChildModel);

        $preCopyEvent = new PreDuplicateModelEvent($environment, $newChildModel, $childModel);
        $dispatcher->dispatch($preCopyEvent, $preCopyEvent::NAME);

        $childDataProvider->save($newChildModel);

        $postCopyEvent = new PostDuplicateModelEvent($environment, $newChildModel, $childModel);
        $dispatcher->dispatch($postCopyEvent, $postCopyEvent::NAME);

        return $newChildModel;
    }

    /**
     * Add postfix at title attribute.
     *
     * @param EnvironmentInterface $environment
     * @param ModelInterface       $sourceModel
     * @param ModelInterface       $newModel
     *
     * @return void
     */
    private function renameModel(
        EnvironmentInterface $environment,
        ModelInterface $sourceModel,
        ModelInterface $newModel
    ): void {
        if (null !== $sourceModel->getProperty('titel')) {
            $newModel->setProperty(
                'titel',
                sprintf('%s (%s)', $sourceModel->getProperty('titel'), 'Kopie')
            );
            $environment->getDataProvider($newModel->getProviderName())->save($newModel);
        }
    }
}
