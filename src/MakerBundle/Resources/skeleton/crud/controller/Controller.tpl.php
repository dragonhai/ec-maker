<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $entity_full_class_name ?>;
use <?= $form_full_class_name ?>;
use <?= $repository_full_class_name ?>;
use Eccube\Controller\AbstractController;
use Eccube\Util\CacheUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class <?= $class_name ?> extends AbstractController
{
    /**
     * @var <?= $repository_var ?><?= PHP_EOL ?>
     */
    protected $<?= $repository_var ?>;

    public function __construct(<?= $repository_class_name ?> $<?= $repository_var ?>)
    {
        $this-><?= $repository_var ?> = $<?= $repository_var ?>;
    }


    /**
     * @Route("/%eccube_admin_route%/<?= $route_name ?>", name="admin_<?= $route_name ?>")
     * @Template("@admin/<?= ucfirst($route_name) ?>/index.twig")
     */
    public function index(Request $request)
    {
        $<?= $entity_twig_var_plural ?> = $this-><?= $repository_var ?>->getList();

        return [
            '<?= $entity_twig_var_plural ?>' => $<?= $entity_twig_var_plural ?>,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/<?= $route_name ?>/<?= $route_name ?>/new", name="admin_<?= $route_name ?>_<?= $route_name ?>_new")
     * @Route("/%eccube_admin_route%/<?= $route_name ?>/<?= $route_name ?>/{<?= $entity_identifier ?>}/edit", requirements={"<?= $entity_identifier ?>" = "\d+"}, name="admin_<?= $route_name ?>_<?= $route_name ?>_edit")
     * @Template("@admin/<?= ucfirst($route_name) ?>/edit.twig")
     */
    public function edit(Request $request, $id = null, RouterInterface $router, CacheUtil $cacheUtil)
    {
        // Initialize the <?= $entity_var_singular ?><?= PHP_EOL ?>
        if (is_null($id)) {
            $<?= $entity_var_singular ?>Origin = null;
            $<?= $entity_var_singular ?> = new <?= $entity_class_name ?>();
        } else {
            $<?= $entity_var_singular ?> = $this-><?= $repository_var ?>->find($id);
            if (!$<?= $entity_var_singular ?>) {
                throw new NotFoundHttpException('<?= strtolower('admin.' . $route_name . '.' . $entity_class_name . '_not_found') ?>');
            }

            $<?= $entity_var_singular ?>Origin = clone $<?= $entity_var_singular ?>;
        }
        
        // Build form
        $builder = $this->formFactory
            ->createBuilder(<?= $form_class_name ?>::class, $<?= $entity_var_singular ?>);

        $form = $builder->getForm();

        // Handle new or update the <?= $entity_var_singular ?><?= PHP_EOL ?>
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $this-><?= $repository_var ?>->save($<?= $entity_var_singular ?>, $<?= $entity_var_singular ?>Origin);
                    $this->addSuccess('admin.common.save_complete', 'admin');
                    return $this->redirectToRoute('admin_<?= $route_name ?>');
                } catch (\Exception $e) {
                    log_info('admin.<?= $route_name ?>.exception.save_error', ['exception' => $e]);
                    $this->addError('admin.common.save_error', 'admin');
                }
            }
        }

        return [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/<?= $route_name ?>/<?= $route_name ?>/{<?= $entity_identifier ?>}/show", requirements={"<?= $entity_identifier ?>" = "\d+"}, name="admin_<?= $route_name ?>_<?= $route_name ?>_show")
     * @Template("@admin/<?= ucfirst($route_name) ?>/show.twig")
     */
    public function show(<?= $entity_class_name ?> $<?= $entity_var_singular ?>)
    {
        return [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/<?= $route_name ?>/<?= $route_name ?>/{<?= $entity_identifier ?>}/delete", requirements={"<?= $entity_identifier ?>" = "\d+"}, name="admin_<?= $route_name ?>_<?= $route_name ?>_delete", methods={"DELETE"})
     */
    public function delete(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>)
    {
        $this->isTokenValid();

        try {
            $this-><?= $repository_var ?>->delete($<?= $entity_var_singular ?>);

            $this->addSuccess('admin.common.delete_complete', 'admin');
        } catch (\Exception $e) {
            log_info('admin.<?= $route_name ?>.exception.delete_error', ['exception' => $e]);
            $this->addError('admin.common.delete_error', 'admin');
        }

        return $this->redirectToRoute('admin_<?= $route_name ?>');
    }
}
