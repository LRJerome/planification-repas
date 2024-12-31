<?php

namespace App\EventListener;

use App\Entity\Repas;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsDoctrineListener(event: Events::preUpdate)]
#[AsDoctrineListener(event: Events::preRemove)]
class ImageDeleteListener
{
    private string $uploadPath;
    private Filesystem $filesystem;

    public function __construct(ParameterBagInterface $params)
    {
        $this->uploadPath = $params->get('kernel.project_dir') . '/public/uploads/repas/';
        $this->filesystem = new Filesystem();
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if (!$entity instanceof Repas) {
            return;
        }

        if ($args->hasChangedField('imageName')) {
            $oldImage = $args->getOldValue('imageName');
            if ($oldImage && $this->filesystem->exists($this->uploadPath . $oldImage)) {
                $this->filesystem->remove($this->uploadPath . $oldImage);
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        
        if (!$entity instanceof Repas) {
            return;
        }

        $imageName = $entity->getImageName();
        if ($imageName && $this->filesystem->exists($this->uploadPath . $imageName)) {
            $this->filesystem->remove($this->uploadPath . $imageName);
        }
    }
} 