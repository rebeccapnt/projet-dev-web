<?php
require('connect.php');

const COVER_PUBLISHED = 2; // Correspond à la valeur du statut si le livre a été publié
const COVER_FINISHED = 1; // Correspond à la valeur du statut si le livre a été configuré mais non publié
const COVER_STARTED = 0; // Correspond à la valeur du statut si le livre n'a pas encore été configuré

function getBestBooks()
{
    $bdd = connectDb();
    $bestBooks = $bdd->query('SELECT * FROM cover ORDER BY nb_reading DESC LIMIT 4');

    return $bestBooks;
}

function getBook($idBook)
{
    $bdd = connectDb();
    $book = $bdd->prepare("SELECT * FROM cover WHERE id_cover =?");
    $book->execute(array($idBook));
    if ($book->rowCount() == 1)
        return $book->fetch();
    else
        throw new Exception("Aucun livre ne correspond à l'identifiant '$idBook'");
}

function getAllBooks()
{
    $bdd = connectDb();
    $sql = $bdd->prepare("SELECT * FROM cover");
    $sql->execute();
    $books = $sql->fetchAll();

    return $books;
}

function getStartedCreation()
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT * FROM cover WHERE writer=? AND status=?');
    $sql->execute(array($_SESSION['id'], COVER_STARTED));
    $startedBooks = $sql->fetchAll();

    return $startedBooks;
}

function getFinishedCreation()
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT * FROM cover WHERE writer=? AND (status=? OR status=?)');
    $sql->execute(array($_SESSION['id'], COVER_PUBLISHED, COVER_FINISHED));
    $finishedBooks = $sql->fetchAll();
    return $finishedBooks;
}

function getPublishedCreation()
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT * FROM cover WHERE writer=? AND status=?');
    $sql->execute(array($_SESSION['id'], COVER_PUBLISHED));
    $publishedBooks = $sql->fetchAll();
    return $publishedBooks;
}

function getStartedReading()
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT * FROM reading WHERE id_user=? AND status=0');
    $sql->execute(array($_SESSION['id']));
    $startedReadings = $sql->fetchAll();
    return $startedReadings;
}

function getFinishedReading()
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT count(DISTINCT id_cover) FROM reading WHERE id_user=? AND status=1');
    $sql->execute(array($_SESSION['id']));
    $finishedReadings = $sql->fetchAll();
    return $finishedReadings;
}

function getBookGenre($genre)
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT * FROM cover WHERE genre=? AND status=? ');
    $sql->execute(array($genre, COVER_PUBLISHED));
    $books = $sql->fetchAll();
    return $books;
}

function insertCover($title, $resume, $genre, $nb_lives, $nb_chapters)
{
    $bdd = connectDb();
    $sql = $bdd->prepare('INSERT INTO cover(title, summary,genre,writer,date,nb_lives,nb_chapters_max,nb_win, nb_reading, status) VALUES (?,?,?,?,?,?,?,0,0,0) ');
    $date = date('Y-m-d');
    $sql->execute([$title, $resume, $genre, $_SESSION['id'], $date, $nb_lives, $nb_chapters]);
    $idNewCover = $bdd->lastInsertId();
    return $idNewCover;
}

///CHAPTER
function insertChapter($idCover, $title, $content, $nb_choices)
{
    $bdd = connectDb();
    $id_next_chapter = getLastChapterId($idCover) + 1;
    $sql = $bdd->prepare('INSERT INTO chapter(id_chapter, id_cover,title,content,nb_choices) VALUES (?,?,?,?,?) ');
    $sql->execute([$id_next_chapter, $idCover, $title, $content, $nb_choices]);
    $idNewChapter = $bdd->lastInsertId();
    return $idNewChapter;
}

function getLastChapterId($id_cover)
{
    $bdd = connectDb();
    $sql = $bdd->prepare("SELECT MAX(id_chapter) AS nbMax FROM chapter WHERE id_cover=?");
    $sql->execute([$id_cover]);
    $nbChapter = $sql->fetchColumn();
    if ($nbChapter) {
        if ($nbChapter != 0) {
            return $nbChapter;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function getChapter($idBook, $idChap)
{
    $bdd = connectDb();
    $sql = $bdd->prepare("SELECT * FROM chapter WHERE id_cover =? AND id_chapter=?");
    $sql->execute(array($idBook, $idChap));
    return $sql->fetch();
}

function getAllChapters($idCover)
{
    $bdd = connectDb();
    $sql = $bdd->prepare("SELECT * FROM chapter WHERE id_cover=?");
    $sql->execute([$idCover]);
    $chapters = $sql->fetchAll();
    return $chapters;
}


///CHOICES

function createChoices($idChap, $idCover)
{
    $bdd = connectDb();
    $sql = $bdd->prepare("SELECT nb_choices FROM chapter WHERE id_chapter=? AND id_cover=?");
    $sql->execute(array($idChap, $idCover));
    $sql->fetch();

    for ($i = 0; $i < $sql; $i++) {
        $bdd = connectDb();
        $sql = $bdd->prepare('INSERT INTO choice(id_cover,id_current_chapter) VALUES (?,?) ');
        $sql->execute([$idCover, $idCover]);
    }
}

function getAllChoices($idCover, $idChapter)
{
    $bdd = connectDb();
    $sql = $bdd->prepare("SELECT * FROM choice WHERE id_current_chapter=? AND id_cover=?");
    $sql->execute(array($idChapter, $idCover));
    $choices = $sql->fetchAll();
    return $choices;
}

function getCover($id_cover)
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT * FROM cover WHERE id_cover=?');
    $sql->execute([$id_cover]);
    $cover = $sql->fetch();

    return $cover;
}

function changeCover($title, $summary, $genre, $nb_lives, $nb_chapters_max, $id_cover)
{
    $bdd = connectDb();
    $sql = $bdd->prepare('UPDATE cover SET title=?, summary=?, genre=? WHERE id_cover=?');

    return $sql->execute([$title, $summary, $genre, $id_cover]);
}


function removeCover($id_cover)
{
    $bdd = connectDb();

    $sql = $bdd->prepare('DELETE FROM cover WHERE id_cover=?');
    return $sql->execute([$id_cover]);
}

function updateNumberReading()
{
    $bdd = connectDb();
    $nbReadings = $bdd->prepare('SELECT nb_reading FROM user WHERE id_user=?');
    $nbReadings->execute($_SESSION['id']);
    $nbReadings++;
    $sql = $bdd->prepare('UPDATE user SET nb_reading=? WHERE id_user=?');
    return $sql->execute([$nbReadings], $_SESSION['id']);
}

function startStory($cover)
{
    $bdd = connectDb();
    //récupération du nombre de vie au début de l'histoire
    $query = $bdd->prepare('SELECT nb_lives FROM cover WHERE id_cover=?');
    $query->execute(array($cover));
    $nbLives = $query->fetch();
    $nbLives = intval($nbLives);
    //insertion dans la bdd du commencement d'une histoire
    $sql = $bdd->prepare('INSERT INTO reading(id_user, id_cover, id_chapter, id_choice, nb_lives) VALUES (?, ?, 1, 0, ?)');
    $sql->execute(array($_SESSION['id'], $cover, $nbLives));
}

function getReadingProgress($cover)
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT * FROM reading WHERE id_user=? AND id_cover=?');
    $sql->execute(array($_SESSION['id'], $cover));
    $sql->fetchAll();
    $chapter = 1;
    foreach ($sql as $ligne) {
        if ($ligne['id_chapter'] > $chapter) {
            $chapter = $ligne['id_chapter'];
        }
    }
    return $chapter;
}

function updateStatusReading($cover, $chapter)
{
    $bdd = connectDb();
    $sql = $bdd->prepare('UPDATE reading SET status=1 WHERE id_user=? AND id_cover=? AND id_chapter=?');
    $sql->execute(array($_SESSION['id'], $cover, $chapter));
}

function deleteReadingStory($cover)
{
    $bdd = connectDb();

    $sql = $bdd->prepare('DELETE FROM reading WHERE id_user=? AND id_cover=?');
    return $sql->execute(array($_SESSION['id'], $cover));
}

function getChoices($cover)
{
    $bdd = connectDb();
    $sql = $bdd->prepare('SELECT id_choice, id_chapter, id_cover FROM reading WHERE id_user=? AND id_cover=?');
    $sql->execute(array($_SESSION['id'], $cover));
    $readingChoices = $sql->fetchAll();
    $choiceNames = array();
    foreach ($readingChoices as $choice) {
        $query = $bdd->prepare('SELECT title FROM choice WHERE id_cover=? AND id_current_chapter=? AND id_choice=?');
        $query->execute(array($choice['id_cover'], $choice['id_chapter'], $choice['id_choice']));
        array_push($choiceNames, $query);
    }
    return $choiceNames;
}
