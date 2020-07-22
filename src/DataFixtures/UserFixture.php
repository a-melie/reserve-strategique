<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $subscriber = new User();
        $subscriber->setEmail('lili@gmail.com');
        $subscriber->setRoles(['ROLE_USER']);
        $subscriber->setPassword($this->passwordEncoder->encodePassword(
            $subscriber,
            'lili'
        ));
        $subscriber->setUsername('lili');
        $manager->persist($subscriber);
        $this->addReference('user_1', $subscriber);


        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));
        $manager->persist($admin);

        $manager->flush();
    }


}
