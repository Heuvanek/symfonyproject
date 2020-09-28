<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\Paginator;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     */
    public function index(CommentRepository $repo, Paginator $paginator, $page)
    {
        $paginator->setEntityClass(Comment::class)
                  ->setCurrentPage($page)
        ;
        return $this->render('admin/comment/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }
    /**
     * Undocumented function
     *  @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $manager
     * @return void
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager){
        $form=$this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le commentaire n°{$comment->getId()} a bien été modifié"
            );

        }
        return $this->render('admin/comment/edit.html.twig',[
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
    /**
     * Undocumented function
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return void
     */
    public function delete(Comment $comment, ObjectManager $manager){
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash(
            'success',
            "Le commentaire <strong>n°{$comment->getId()}</strong> a bien été supprimée"
        );
        return $this->redirectToRoute('admin_comments_index');    
    }
}
