# Argomenti delle lezioni e riferimenti

I vari comandi di setup del progetto e del container sono quelli usati a lezione. La branch master viene aggiornata dopo
ogni lezione (salvo imprevisti). Se si è saltata una lezione o si è rimasti indietro, molti di questi comandi possono
essere sostituiti da:
1) Aggiornare il repository locale con le modifiche della lezione

   A seconda di quanto siete ferrati con git, potete lanciare `git pull origin master` e poi risolvere tutti i conflitti
   che inevitabilmente ci saranno, oppure potete abbandonare la vostra branch e passare direttamente a quella della
   lezione con
   ```shell
   $ git fetch origin <nome della branch>
   $ git switch <nome della branch>
   ```
2) Avviare l'ambiente
   ```shell
   $ make start
   ```
3) Aprire una shell all'interno del container
   ```shell
   $ make shell
   ```
4) Aggiornare le dipendenze tramite composer
   ```shell
   # all'interno del container
   $ composer install --dev
   ```

## Lezione 1 - Configurazione dell'ambiente
Durante questa lezione abbiamo configurato l'ambiente e installato Symfony (il binario) e con esso poi il framework
Symfony.

Su linux, per evitare di dover usare `sudo` per lanciare i comandi docker, seguire [questa guida](https://docs.docker.com/engine/install/linux-postinstall/#manage-docker-as-a-non-root-user)

Lista dei comandi usati:
```shell
$ make start
# o in alternativa, senza usare make
$ docker compose up -d
# verificare su localhost:8080 la presenza di una pagina web, poi rimuovere la cartella public e il suo contenuto
$ make shell
# o senza make
$ docker exec -it web bash
# da questo momento i comandi vanno lanciati all'interno del container
$ git config --global user.email "example@example.com"
wget https://get.symfony.com/cli/installer -O - | bash
# utlizzando il binario di symfony appena installato
$ symfony new --no-git --version=^6.4
```

Argomenti toccati a lezione:
- make,
- Dockerfile e docker-compose.yml,
- elementi di configurazione di Apache,
- cos'è semantic versioning ([semver](https://semver.org)),
- composer come package manager e come autoloader,
- ambienti di esecuzione di symfony (dev, test, prod),
- struttura delle directory del progetto,
- cosa sono i symfony bundles,
- installazione di PHPStorm e dei plugin PHP Toolbox, Symfony.

La [Branch "lesson-one-end"](https://github.com/RBastianini/labingsoft/tree/lesson-one-end) contiene lo stato del
  repository alla fine della lezione.


## Lezione 2 - Controller, rotte, view, toolbar di debug e traduzione

Durante questa lezione abbiamo iniziato a vedere il pattern MVC, nello specifico le parti VIEW e CONTROLLER
Abbiamo iniziato creando alcuni controller, alcune action e associando ad esse delle rotte utilizzando gli attributi
PHP.
Abbiamo visto come mostrare le rotte tramite la console di symfony
```shell
# Dall'interno del container
$ bin/console debug:router
```
come cambiare nome alle rotte e come ottenere dei parametri tramite porzioni del path delle rotte.

Inizialmente abbiamo restituito dell'html scritto manualmente tramite degli oggetti Response dalle action dei controller
e accennato ai pericoli del [XSS](https://owasp.org/www-community/attacks/xss/).

Abbiamo installato php-cs-fixer e PHPStan e accennato al funzionamento dell'analisi statica.

```shell
# Dall'interno del container
$ composer require --dev cs-fixer-shim
$ composer require --dev phpstan/phpstan
$ composer require --dev phpstan/phpstan-beberlei-assert
```
e usato entrambi gli strumenti per controllare il codice scritto e per correggerlo
```shell
# Dall'interno del container
$ vendor/bin/php-cs-fixer check -v
$ vendor/bin/php-cs-fixer fix
$ vendor/bin/phpstan analyse
```
.

Abbiamo quindi installato twig
```shell
# Dall'interno del container
$ composer install twig
```
e notato come alcuni pacchetti abbiano un nome composto da due parti separate da uno slash, e altri no. Abbiamo quindi
accennato a [packagist](https://packagist.org/) e a come Symfony fornisce degli alias per alcuni pacchetti di uso comune
([qui](https://github.com/symfony/recipes) la lista degli alias disponibili).

Abbiamo quindi modificato i controller definiti prima per utilizzare twig per renderizzare le risposte, utilizzando il
metodo `render()` di `AbstractController`. Abbiamo visto la sintassi `{% %}` e `{{ }}` di twig.
Abbiamo aggiunto asset-mapper per gestire gli asset frontend
```shell
# Dall'interno del container
$ composer install asset-mapper
```
e utilizzato lo stesso per aggiungere bootstrap ai template
```shell
# Dall'interno del container
$ bin/console importmap:require bootstrap
```
 
Abbiamo installato il profiler di Symfony e fatto un tour della toolbar di debug
```shell
# Dall'interno del container
$ composer require --dev profiler
```
Infine, abbiamo aggiunto il pacchetto di traduzione e visto come tradurre le stringhe dei template.
```shell
# Dall'interno del container
$ composer require translation
```

### Da fare per casa
Fare in modo che sia possibile navigare verso `localhost:8080/weather/STATO/CITTÀ` dove `STATO` è un codice di 2 lettere
che rappresenta una nazione e `CITTÀ` è il nome di una città. Quando un utente richiede questa pagina, deve essere
restituita una risposta renderizzata con twig, usando la stessa struttura dei file vista a lezione, contenente il testo:
"Ecco le previsioni del tempo per CITTÀ (STATO)", dove CITTÀ e STATO devono variare sulla base dell'URL della richiesta.
Esempio:
Se l'utente naviga verso `localhost:8080/weather/IT/Roma`, la pagina dovrà contenere "Ecco le previsioni del tempo per
Roma (IT)".

### Riferimenti
- https://symfony.com/doc/6.4/routing.html
- https://symfony.com/doc/6.4/templates.html
- https://symfony.com/doc/6.4/controller.html
- https://symfony.com/doc/6.4/frontend/asset_mapper.html
- https://symfony.com/doc/6.4/translation.html#translations-in-templates

La [Branch "lesson-two-end"](https://github.com/RBastianini/labingsoft/tree/lesson-two-end) contiene lo stato del repository alla fine della lezione.

## Lezione 3 - Model, Doctrine, migrazioni e comandi della console
Abbiamo iniziato la lezione aggiungendo il controller `WeatherController`, la rotta per le previsioni e il relativo
template che insieme componevano la soluzione dell'esercizio dell'altra lezione. Abbiamo poi aggiunto un ulteriore
template `_navBar.html.twig` e abbiamo visto la funzione
[`include()`](https://twig.symfony.com/doc/3.x/tags/include.html) per il rendering del template parziale. Prima di
proseguire abbiamo anche modificato la rotta del controller per aggiungere dei requisiti per il matching, per fare in
modo che la rotta venisse riconosciuta solo quando il codice dello stato fosse composto da 2 lettere, e la città da sole
lettere e numeri.

Abbiamo quindi fatto un esercizio di modellazione, discutendo su quali proprietà avrebbero dovuto avere le nostre entità 
di tipo "Previsione del tempo" (Forecast) e nel farlo abbiamo anche dedeterminato l'esistenza di un'entità "Luogo"
(Location).
Abbiamo scritto la struttura di un oggetto Forecast seguendo il nostro modello, utilizzando però un array semplice, al
solo scopo di avere una struttura base da utilizzare per popolare una view, utilizzando un altro modello parziale
`_forcastCard.html.twig`.

Abbiamo quindi installato l'ORM "Doctrine" (e il plugin per phpstan)
```shell
# Dall'interno del container
# Alla domanda riguardante docker, rispondere no: il database su docker è già configurato
$ composer require orm
$ composer require --dev phpstan/phpstan-doctrine
```

Il passaggio successivo è stato quello di configurare l'accesso al database, modificando la variabile `DATABASE_URL`
all'interno del nostro file `.env`:
`DATABASE_URL="postgresql://dbuser:segreta@db:5432/app?serverVersion=16&charset=utf8"` e per finire abbiamo verificato
che l'applicazione fosse in grado di collegarsi al database, tramite il comando
```shell
# Dall'interno del container
$ bin/console doctrine:schema:create
```
Dopo aver configurato l'accesso al database tramite PHPStorm per poter controllare lo stato delle tabelle, abbiamo
definito la nostra prima entità `Location` come un semplice oggetto PHP (POPO). Nel farlo abbiamo discusso di quali
fossero le proprietà obbligatorie per la creazione di una Location ("cosa rende un luogo un luogo") e abbiamo
quindi aggiunto queste proprietà come obbligatorie nel costruttore.

Abbiamo quindi aggiunto gli attributi di Doctrine per trasformare la nostra entità `Location` in una entità Doctrine.
Dopo questo passaggio abbiamo affrontato l'argomento delle migrazioni e infine utilizzando la console abbiamo generato
ed eseguito (dopo qualche piccola modifica) la nostra prima migrazione tramite Doctrine.

```shell
# Dall'interno del container
$ bin/console doctrine:schema:diff
$ bin/console doctrine:migrations:migrate
```

Per finire, abbiamo iniziato la definizione un comando da console `app:location:create` per poter aggiungere dati al
nostro database. Riprenderemo la lezione da questo punto.

### Da fare per casa
Provare ad aggiungere la definizione del modello per `Forecast`, come abbiamo fatto per `Location`. Definire le 
proprietà di Forecast seguendo la struttura dell'array che abbiamo passato alla view di `ForecastController::index()`
```php
$forecast = [
    'day' => new \DateTimeImmutable('today'),
    'location' => [
        'name' => 'Perugia',
        'country' => 'IT',
    ],
    'shortDescription' => 'SUNNY',
    'minimumCelsiusTemperature' => 5,
    'maximumCelsiusTemperature' => 20,
    'windSpeedKmh' => 2,
    'humidityPercentage' => 0.30,
];
```
Non abiamo visto come si definiscono tutte le proprietà, quindi occorrerà consultare la
[documentazione sui tipi](https://www.doctrine-project.org/projects/doctrine-orm/en/3.3/reference/basic-mapping.html#basic-mapping),
la [documentazione sulle relazioni](https://www.doctrine-project.org/projects/doctrine-orm/en/3.3/reference/association-mapping.html#association-mapping).

### Riferimenti
- https://symfony.com/doc/6.4/configuration.html#config-dot-env
- https://symfony.com/doc/6.4/routing.html#parameters-validation
- https://vimeo.com/channels/phpday/176057940 (richiede un account vimeo - gratuito)
- https://martinfowler.com/eaaCatalog/dataMapper.html
- https://www.doctrine-project.org/projects/doctrine-orm/en/3.3/reference/basic-mapping.html#basic-mapping
- https://www.doctrine-project.org/projects/doctrine-orm/en/3.3/reference/association-mapping.html#association-mapping
- https://symfony.com/bundles/DoctrineMigrationsBundle/current/index.html#generating-migrations-automatically
- https://symfony.com/doc/6.4/console.html#creating-a-command

La [Branch "lesson-three-end"](https://github.com/RBastianini/labingsoft/tree/lesson-three-end) contiene lo stato del
repository alla fine della lezione.

## Lezione 4 - Entità, Relazioni, Value Object, Repository.
Nello svolgere insieme l'esercizio lasciato per casa l'altra volta, abbiamo visto come definire relazioni tra le entità
e come definire relazioni dirette e inverse usando gli attributi. Abbiamo anche brevemente toccato l'argomento dei
vincoli, aggiungendo dei vincoli di unicità sia su `Location` che su `Forecast`. Parlando delle proprietà temperatura
minima e massima di `Forecast` e nell'ottica di impedire la creazione di entità non valide (e la manipolazione di entità
valide fino a farle diventare invalide), abbiamo cercato un modo per impedire di violare il vincolo
`minimumCelsiusTemperature <= maximumCelsiusTemperature`. Dopo alcuni tentativi, la soluzione al problema è arrivata
introducendo il concetto di **Value Object** e creando il nostro primo value object: `TemperatureSpan`. 
Abbiamo visto la definizione di un `enum` (`ShortWeatherDescription`) come modo per vincolare una proprietà ad un
insieme finito di valori discreti noto a priori.
Siamo quindi tornati al comando di console di Symfony `CreateLocationCommand` che avevamo lasciato in sospeso sul finire
della lezione precedente. Abbiamo usato `EntityManagerInterface` per creare una nuova entità di tipo Location e per
persisterla nel database. Abbiamo quindi creato un comando `LocationPlaygroundCommand` per vedere altri utilizzi di
`EntityManagerInterface` per caricare entità dal database.
Siamo quindi passati a definire ed utilizzare il **Repository Pattern**, creando `LocationRepository` ed ereditando da
`ServiceEntityRepository` per vedere quali funzionalità sono a disposizione nei repository di Doctrine.
Abbiamo visto la sintassi PHPDoc per definire i tipi specifici di oggetti "*generics*" in PHP e indagato come fanno i
repository Doctrine ad avere dei metodi il cui nome dipende dalle proprietà definite nelle entità da essi gestiti,
tramite i **metodi magici**.
Infine, abbiamo rapidamente modificato la action `index` del nostro `ForecastController` per caricare un'entità di tipo
`Location` dal database sulla base del nome, per poi ottenere da quella gli oggetti `Forecast` relazionati e stamparne
uno, al posto di usare un array statico come alla fine della lezione precedente.

### Da fare per casa
Aggiungere un repository anche per `Forecast` come abbiamo fatto per `Location`.
Creare un comando come `CreateLocationCommand` per creare oggetti `Forecast`. Notare quali sono le proprietà intrinseche
di `Forecast` che non possono mancare e quelle facoltative. Usare queste informazioni per configurare argomenti e
opzioni del comando.
Inserire qualche previsione per le città che sono presenti nel database.

### Riferimenti
- https://www.doctrine-project.org/projects/doctrine-orm/en/3.3/reference/association-mapping.html#association-mapping
- https://martinfowler.com/eaaCatalog/valueObject.html
- https://phpstan.org/writing-php-code/phpdoc-types#general-arrays
- https://phpstan.org/writing-php-code/phpdoc-types#iterables
- https://phpstan.org/blog/generics-by-examples
- https://martinfowler.com/eaaCatalog/repository.html
- https://www.php.net/manual/en/language.oop5.magic.php
- https://en.wikipedia.org/wiki/Elvis_operator
- https://symfony.com/doc/6.4/doctrine.html#fetching-objects-from-the-database

La [Branch "lesson-four-end"](https://github.com/RBastianini/labingsoft/tree/lesson-four-end) contiene lo stato del 
repository alla fine della lezione.

## Lezione 5 - Repository, Query builder, N+1 query problem e soluzioni
La lezione è iniziata applicando insieme le correzioni presenti in
[questo commit](https://github.com/RBastianini/labingsoft/commit/3edbd4d7f3b1fa731a6daa0080462c369fe6b205) ed è
proseguita svolgendo insieme l'esercizio lasciato la lezione precedente, costruendo un comando per l'inserimento di
entità Forecast nel database. Nel farlo, abbiamo visto come istanziare oggetti `DateTime` ed `Enum` a partire da
stringhe e come utilizzare l'annotazione `ORM\Entity` per indicare a Doctrine quale classe sia il **Repository** di
un'entità, in aggiunta a come utilizzare le opzioni dei comandi di Symfony CLI. Abbiamo personalizzato il nostro
**Repository** delle `Location` per avere un metodo in grado di cercare `Location` per nome e per stato, per poi
utilizzarlo nella rotta di `WeatherController::index`, facendo così in modo che venga restituita una previsione per la
città specificata nell'URL. Per farlo abbiamo utilizzato il `Doctrine Query Builder` e accennato al **Builder pattern**.
Successivamente, abbiamo aggiunto una rotta per mostrare tutte le città e le previsioni ad esse associate presenti nel
nostro database. La prima implementazione, più semplice, è stata usata per introdurre il problema delle **N+1 query**,
per risolvere il quale abbiamo usato sia la tecnica del *fetch join* che quella della *multi-step hydration*.

### Da fare per casa
Aggiungere un controller `LocationController` e una rotta `index` per mostrare tutte le location presenti nel database,
utilizzando la paginazione caricando solo 10 per pagina e mostrando i link per andare alla pagina precedente e seguente.

### Riferimenti
- https://www.doctrine-project.org/projects/doctrine-orm/en/3.3/reference/dql-doctrine-query-language.html
- https://symfony.com/doc/6.4/doctrine.html#querying-for-objects-the-repository
- https://www.doctrine-project.org/projects/doctrine-orm/en/3.3/reference/query-builder.html
- https://dev.to/lovestaco/the-n1-query-problem-the-silent-performance-killer-2b1c
- https://ocramius.github.io/blog/doctrine-orm-optimization-hydration/
- https://dev.to/andreasprega/entity-repositories-for-humans-part-1-2mnf

La [Branch "lesson-five-end"](https://github.com/RBastianini/labingsoft/tree/lesson-five-end) contiene lo stato del
repository alla fine della lezione.

## Lezione 6 - Fixtures, SOLID principles, form e validazione
A inizio lezione abbiamo visto una possibile soluzione dell'esercizio, con una versione semplificata di paginazione per
la lista delle location. In seguito è stato mostrato come preparare e inserire delle fixtures, mediante il relativo
pacchetto
```shell
# Dall'interno del container
$ composer require --dev orm-fixtures
```
e il comando
```shell
# Dall'interno del container
$ bin/console doctrine:fixtures:load
```
.
Si è proseguito elencando e definendo i SOLID principles e illustrandone i benefici.
Nella seconda parte della lezione abbiamo iniziato a vedere i **Form** di Symfony, a partire dall'installazione del
pacchetto composer
```shell
# Dall'interno del container
$ composer require form
```
Abbiamo quindi visto il pattern **Data Transfer Object** e siamo tornati anche a parlare di **Value Object** per
evidenziare le differenze tra i due. Abbiamo quindi definito dei DTO per `Forecast` e per `TemperatureSpan` e li abbiamo
utilizzati come supporto per la definizione di altrettanti form. Nel definire e innestare i due form, abbiamo accennato
al **Composite Pattern** usato da `FormTypeInterface` per rendere i form facilmente componibili.
Utilizzando il comando della console
```shell
# Dall'interno del container
$ bin/console debug:form
```
abbiamo visto quali `FormType` sono quelli disponibili all'uso e ne abbiamo usati e configurati alcuni come `NumberType`
`ChoiceType` e `EntityType`.
Abbiamo quindi definition una **Action** *create* nel `ForecastController` e passato il form alla view per il rendering,
vedendo sia come personalizzare la visualizzazione tramite il tema di bootstrap 5 per i form di Symfony, sia come
personalizzare il rendering mediante le varie funzioni twig `form_row()`, `form_error()`, `form_label()`, `form_help()`,
`form_widget()`, `form_start()` e `form_end()`.
Per finire, abbiamo installato il pacchetto Symfony per la validazione
```shell
# Dall'interno del container
$ composer require validator
```
ed utilizzato gli attributi forniti per aggiungere validazione a `ForecastDTO`.
Abbiamo aggiornato la **Action** *create* per validare il form, prima di prenderne il contenuto, creare un Forecast e
salvarlo, utilizzando `FormInterface::handleRequest()`, `FormInterface::isSubmitted()` e `FormInterface::isValid()`.

### Da fare per casa
Aggiungere tutto il necessario per poter creare delle `Location` tramite l'interfaccia web:
- Controller,
- Action,
- Form,
- DTO (con regole di validazione),
- View.

Suggerimento: anche se l'entità ha la relazione `Forecasts`, ignorate questa proprietà nella definizione di **DTO** e
**Form**: vogliamo creare solo una `Location`, abbiamo già tutto il codice necessario per creare i `Forecast`.

### Riferimenti
- https://symfony.com/doc/current/the-fast-track/en/17-tests.html#defining-fixtures
- https://learn.microsoft.com/en-us/archive/msdn-magazine/2014/may/csharp-best-practices-dangers-of-violating-solid-principles-in-csharp
- https://symfony.com/doc/6.4/forms.html
- https://symfony.com/doc/6.4/reference/forms/types.html

La [Branch "lesson-six-end"](https://github.com/RBastianini/labingsoft/tree/lesson-six-end) contiene lo stato del
repository alla fine della lezione.

# Lezione 7 - Dependency Injection Container e Unit Test
La lezione è iniziata con l'illustrazione di una possibile soluzione per l'esercizio lasciato dirante la lezione
precedente.
Per risolvere l'esercizio è stato installato il pacchetto symfony/intl tramite il comando
```shell
# Dall'interno del container
$ composer require intl
```
per poter utilizzare il **FormType** `CountryType`.
In aggiunta, è stato mostrato come creare dei vincoli di validazione (e i relativi validatori) personalizzati.
Infine è stato mostrato un modo di organizzare il codice tramite **Intent** (o **Command**) e **IntentHandler**
(o **CommandHandler**), che si è rivelato utile sia per rimuovere i `SubmitType` dalle varie classi **___DTOForm** che
abbiamo definito e spostarli in dei form dedicati, sia per organizzare il codice necessario al processamento del form,
separandolo dal codice necessario per creare il form e renderizzare la view, che è rimasto invece nel controller.
La lezione è proseguita illustrando la differenza tra "*classi che contengono informazioni*" come i **modelli**,
**ValueObject** o **DTO** e le classi che "*sanno fare qualcosa*" come i **Form**, **Controller** e **IntentHandler**,
che abbiamo chiamato **Servizi**. Abbiamo parlato del **Dependency Injection Container** di Symfony e di come è
configurato. Abbiamo visto quali servizi sono definiti tramite il comando
```shell
# Dall'interno del container
$ bin/console debug:autowiring --all
```
e usato questa informazione per correggere la configurazione affinché non fossero aggiunte al container le "*classi che
contengono informazioni*".

Oltre ai servizi abbiamo visto cosa sono e come definire i **Parameters** nel container; come visualizzare quelli
esistenti con il comando
```shell
# Dall'interno del container
$ bin/console debug:container --parameters
```
e come fare a recuperare il valore di un parametro dai controller.
Infine, abbiamo visto il comando
```shell
# Dall'interno del container
$ bin/console debug:dotenv
```
per elencare le variabili d'ambiente definite e visualizzare il loro contenuto.

Siamo quindi passati all'argomento testing, iniziato da una descrizione dei principali tipi di test automatici del
codice: **Unit test**, **Integration test** e **End to end test**. Abbiamo parlato della piramide del testing e siamo
poi passati a scrivere dei test unitari, iniziando dall'installazione di **PHPUnit** tramite
```shell
# Dall'interno del container
$ composer require --dev symfony/test-pack
```
.
Abbiamo quindi creato un primo test unitario, vedendo l'utilizzo dell'annotazione `@test` per marcare i metodi di test,
l'uso delle asserzioni e l'uso dell'annotazione `@dataProvider` per rendere i test parametrici.

È stato anche utilizzato il comando
```shell
# Dall'interno del container
$ vendor/bin/phpunit
```
per eseguire tutti i test presenti.

### Da fare per casa
Utilizzando la struttura di **Intent** e **IntentHandler** fornita a lezione, creare una action, form, intent e intent
handler per modificare una **Location** esistente.

### Riferimenti
- https://symfony.com/doc/6.4/validation/custom_constraint.html
- https://symfony.com/doc/6.4/service_container.html
- https://docs.phpunit.de/en/10.5/writing-tests-for-phpunit.html
- https://martinfowler.com/articles/practical-test-pyramid.html


La [Branch "lesson-seven-end"](https://github.com/RBastianini/labingsoft/tree/lesson-seven-end) contiene lo stato del
repository alla fine della lezione.