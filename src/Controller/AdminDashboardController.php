<?php

namespace App\Controller;

use App\Service\Statistor;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, Statistor $Statistor)
    {
        $users = $Statistor->getUsersCount();
        $ads = $Statistor->getAdsCount();
        $bookings = $Statistor->getBookingsCount();
        $comments = $Statistor->getCommentsCount();
        $bestads = $Statistor->getAdsStats('DESC');
        $worstads = $Statistor->getAdsStats('ASC');
        return $this->render('admin/dashboard/index.html.twig', [
                'stats' =>compact('users',
                                  'ads', 
                                  'bookings',
                                  'comments', 
                                  'bestads',
                                  'worstads'
                          )
                ]);
    }
}
