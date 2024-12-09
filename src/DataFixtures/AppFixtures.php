<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AppFixtures extends Fixture
{    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur
        $user = new User();
        $user->setUsername('john_doe');
        $user->setEmail('john@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $manager->persist($user);

        // Création de quelques posts
        $post1 = new Post();
        $post1->setTitle('Mon premier post');
        $post1->setContent('Voici le contenu de mon premier post.');
        $post1->setImage('image1.jpg');
        $post1->setUser($user); // L'utilisateur est associé au post

        $post2 = new Post();
        $post2->setTitle('Deuxième post');
        $post2->setContent('Voici le contenu de mon deuxième post.');
        $post2->setImage('image2.jpg');
        $post2->setUser($user); // L'utilisateur est associé au post
        // Ajout de likes
        $post1->addLike($user);  // L'utilisateur aime le premier post
        $post2->addLike($user);  // L'utilisateur aime le deuxième post

        $manager->persist($post1);
        $manager->persist($post2);

        // Création de commentaires pour les posts
        $comment1 = new Comment();
        $comment1->setContent('C\'est un super post !');
        $comment1->setCreatedAt(new \DateTimeImmutable());
        $comment1->setPost($post1); // Le commentaire est associé au post1
        $comment1->setUser($user);  // L'utilisateur est associé au commentaire

        $comment2 = new Comment();
        $comment2->setContent('J\'aime beaucoup ce post.');
        $comment2->setCreatedAt(new \DateTimeImmutable());
        $comment2->setPost($post2); // Le commentaire est associé au post2
        $comment2->setUser($user);  // L'utilisateur est associé au commentaire

        $manager->persist($comment1);
        $manager->persist($comment2);

        // Sauvegarder les entités dans la base de données
        $manager->flush();
    }
}
