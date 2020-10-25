<?php

/**
 * http://strategico-dev.ru/refactoring-1/
 * 
 * @property string $name
 * @property string $title
 * @property string $content
 * @property User $creator
 */
class Document
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $content;

    /**
     * @var User
     */
    public $creator;

    /**
     * @var string $name
     * @var string $title
     * @var string $content
     * @var User $creator
     */
    public function __construct(string $name, string $title, string $content, User $creator)
    {
        $this->name = $name;
        $this->title = $title;
        $this->content = $content;
        $this->creator = $creator;
    }

    public function save()
    {
        return DatabaseFacade::save('document', get_object_vars($this));
    }

    /**
     * @var string $name
     * @return Document
     * @throws Exception
     */
    public static function findByName(string $name) : Document
    {
        $documentData = DatabaseFacade::prepare('SELECT * FROM document WHERE name = ? LIMIT 1')->
                                        execute([$name]);

        if($documentData == null)
        {
            throw new Exception('Document not found');
        }

        return DocumentFactory::factory($documentData);
    }

    /**
     * @var User $user
     * @return array[Document]
     */
    public static function findAllByUser(User $user) : array
    {
        $documentsData = DatabaseFacade::prepare('SELECT * FROM document WHERE creator_id = ?')->
                                         execute([$user->id]);
        
        return DocumentFactory::factory($documentsData);
    }


}

/**
 * @property int $id
 */
class User
{
    /**
     * @var int
     */
    public $id;

    
    /**
     * @var int $id
     * @throws Exception
     */
    public static function findById(int $id) : User
    {
        $userData = Database::prepare('SELECT * FROM user WHERE id = ?')->
                              execute([$id]);

        if($userData == null)
        {
            throw new Exception('User not found');
        }

        return UserFactory::factory($userData);
    }
}

function main()
{
    /**
     * Find a user
     */
    $user = User::findById(1);

    /**
     * Creater a document
     */
    $document = new Document('Name', 'Title', 'Content', $user);

    /**
     * Save a document
     */
    $document->save();

    /**
     * Find all documents
     */
    $userDocuments = Document::findAllByUser($user);
}