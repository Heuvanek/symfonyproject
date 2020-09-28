<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr'); 
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $adminUser = new User();
        $adminUser->setFirstName('Emeric')
                  ->setLastName('Sini')
                  ->setEmail('emeric.sini@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://pbs.twimg.com/profile_images/1263173283882708993/yoPoi_Xu_400x400.jpg')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        ;
        $manager->persist($adminUser);
        $adminUser2 = new User();
        $adminUser2->setFirstName('Romuald')
                  ->setLastName('MicroPenis')
                  ->setEmail('rom.micro@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://www.researchgate.net/profile/Sultan_Kaba/publication/295859353/figure/fig1/AS:332967512690688@1456397049379/Micro-penis-and-scrotal-hypoplasia.png')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        ;
        $manager->persist($adminUser2);
        $users = [];
        $genres = ['male', 'female'];
        for($i = 1; $i <= 30; $i++){
            $user = new User();
            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
            $hash = $this->encoder->encodePassword($user, 'password');
            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setPicture($picture)
                 ->setHash($hash)
            ;
        $manager->persist($user);
        $users[] = $user;
        }
        for($i = 1; $i <= 30; $i++){

            $ad = new Ad();
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $contentt = '<p>'.join('</p><p>', $faker->paragraphs(6)) . '</p>';
            $user = $users[mt_rand(0, count($users) - 1)];
            $ad->setTitle($title)
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContentt($contentt)
            ->setPrice(mt_rand(40,400)) 
            ->setRooms(mt_rand(1, 10))
            ->setAuthor($user)
            ; 
            for($j = 1; $j <= mt_rand(2, 5); $j++){
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                      ->setCaption($faker->sentence())
                      ->setAd($ad);
                $manager->persist($image);
            }
            for($j=1; $j<=mt_rand(0,15); $j++){
                $booking = new Booking();
                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                $duration = mt_rand(3, 15);
                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0, count($users) - 1)];
                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($faker->paragraph())
                ;
                $manager->persist($booking);

                if(mt_rand(0, 1)){
                    $comment = new Comment();
                    $comment->setContent($faker->paragraph())
                            ->setRating(mt_rand(1, 5))
                            ->setAuthor($booker)
                            ->setAd($ad)
                    ;
                    $manager->persist($comment);
                }
            }
            $manager->persist($ad);
        } 
        $manager->flush();
    }
}
