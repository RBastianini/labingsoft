<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LocationControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        // NOTE: in a real functional test suite we would not be doing this here (and we would not be doing it using
        // exec), but here we only have a handful of tests, so we can afford to delete and recreate the database
        // before each test to ensure a clean environment.
        exec('bin/console --env=test doctrine:database:create --if-not-exists --quiet');
        exec('bin/console --env=test doctrine:schema:drop --force --quiet');
        exec('bin/console --env=test doctrine:schema:create --quiet');
    }

    /**
     * @test
     */
    public function an_user_can_create_a_new_location(): void
    {
        // This will simulate our web browser
        $client = static::createClient();

        // Create an admin, we'll need one to be able to create a new location
        $admin = new User('admin@example.com', null);
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $this->getContainer()->get(UserPasswordHasherInterface::class);
        $admin->setPassword($passwordHasher->hashPassword($admin, 'generata casualmente'));
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($admin);
        $entityManager->flush();

        // Ensure the client automatically follows redirects (we redirect the user after a successful form submission)
        $client->followRedirects();

        // To create a new location, we require the user to be authenticated. This "forces" the user to be authenticated
        // without having to submit the login form.
        $client->loginUser($admin);

        // Load the application
        $homePage = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        // Notice how, although the create_location link is in a submenu, we don't have to click on the parent element
        // first, in order to access it. This is because this is not an end-to-end test, and we're not using a real
        // browser: we are just accessing the page HTML, not its visualization.
        $createPage = $client->click($homePage->selectLink('navigation.create_location')->link());

        $this->assertResponseIsSuccessful();
        // We want to fill the form, and the recommended way to do so is to first find the submit button...
        $submitButton = $createPage->selectButton('save');
        //  ... then from the submit button, get the form.
        $form = $submitButton->form();

        // Fill the form fields...
        $form->get('create_location_intent_form[locationDTO][name]')
            ->setValue('Roma');
        /** @var ChoiceFormField $selectField */
        $selectField = $form->get('create_location_intent_form[locationDTO][country]');
        $selectField->select('IT');
        $form->get('create_location_intent_form[locationDTO][latitude]')
            ->setValue('42');
        $form->get('create_location_intent_form[locationDTO][longitude]')
            ->setValue('42');

        // Submit the form
        $resultPage = $client->submit($form);

        // We expect to end up in the pages with the forecast for the city we just added
        $this->assertResponseIsSuccessful();
        $resultPage->text('weather_forecasts.here_are_the_weather_forecasts_for_Roma_IT');
    }
}
