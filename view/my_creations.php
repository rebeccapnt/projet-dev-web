<?php
$title = 'UStory - Mes créations';
ob_start();
?>
    <div class="container my-3">
    <h2 class="h2_title my-2">Mes créations</h2>
    <hr class="hr_content mb-5"/>


<?php
if (isset($_SESSION['nickname']) && isset($_SESSION['role'])) {
    //s'il n'y a pas encore d'histoire créée
    if (empty($startedBooks) && empty($finishedBooks)) {
        ?>
        <div class="container my-5">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <div class="card text-center" style="width: 30rem;">
                        <div class="card-body">
                            <i class="fa fa-book fa-2x mb-4" style="color:#0883cd;"></i>
                            <p class="mb-4">Vous n'avez pour l'instant créé aucune histoire. <br/>Commencez à écrire
                                votre première histoire dès maintenant ! </p>
                            <a class="btn btn-success" href="index.php?action=creer-histoire">Créer mon
                                histoire</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else {
        //s'il y a des histoires en cours de création
        if (!empty($startedBooks)) { ?>
            <div class="row mb-5 d-flex justify-content-center justify-content-lg-between align-items-center infos bg-light p-3 m-0">
                <h4 class="intitule mx-2 text-center text-lg-left">Histoires en cours de création</h4>
            </div>
            <div class="row mb-5">
                <?php
                foreach ($startedBooks
                         as $book) { ?>
                    <div class="col-6 col-lg-3 mb-5 mb-lg- ">
                        <div class="card ">
                            <img src="public/images/blankpage.jpg" class="card-img-top" alt="book_img">
                            <div class="card-body">
                                <h5 class="card-title text-truncate"><?= $book['title'] ?></h5>
                                <p class="card-text  text-truncate--3"><?= $book['summary'] ?></p>
                                <div class="row">
                                    <div class="d-grid gap-2 text-center">
                                        <a href="index.php?action=afficher-livre&id=<?= $book['id_cover'] ?>"
                                           class="btn btn-primary px-2">Editer</a>
                                    </div>
                                    <div class="d-grid gap-2 text-center">
                                        <button type="button" class="btn btn-danger mt-2" data-toggle="modal"
                                                data-target="#modalDelete<?= $book['id_cover'] ?>">
                                            Supprimer
                                        </button>
                                        <div class="modal fade" id="modalDelete<?= $book['id_cover'] ?>" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLongTitle">Supprimer une
                                                            histoire</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Etes-vous sûr de vouloir supprimer cette histoire ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Fermer
                                                        </button>
                                                        <form name="delete_cover" method="POST">
                                                            <a href="index.php?action=supprimer-livre&id=<?= $book['id_cover'] ?>"
                                                               class="btn btn-danger btn-block my-2 px-2">Supprimer</a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }
        //s'il y a des histoires finies
        if (!empty($finishedBooks)) { ?>
            <div class="row mb-5 d-flex justify-content-center justify-content-lg-between align-items-center infos bg-light p-3 m-0">
                <h4 class="intitule mx-2 text-center text-lg-left">Histoires terminées</h4>
            </div>

            <div class="row mb-5">
                <?php
                foreach ($finishedBooks as $book) { ?>
                    <div class="col-6 col-lg-3 mb-5 ">
                        <div class="card ">
                            <img src="public/images/cover4.jpg" class="card-img-top" alt="book_img">
                            <div class="card-body">
                                <h5 class="card-title text-truncate book_title"><?= $book['title'] ?></h5>
                                <span class="badge badge-pill badge_style mb-3"><?= $book['genre'] ?></span>
                                <p class="card-text  text-truncate--3"><?= $book['summary'] ?></p>
                                <?php
                                if ($book['status'] == 2) { ?>
                                    <div class="row ">
                                        <div class="col-12 col-lg-6  mb-2 ">
                                            <button type="button" class="btn px-2  btn-success btn-block mb-2"
                                                    data-toggle="modal" data-target="#modal<?= $book['id_cover'] ?>">
                                                <i class="bi bi-eye"></i> Publié
                                            </button>
                                            <div class="modal fade" id="modal<?= $book['id_cover'] ?>" tabindex="-1"
                                                 role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Cacher
                                                                une histoire</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Etes-vous sûr de vouloir cacher cette histoire ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Fermer
                                                            </button>
                                                            <a href="index.php?action=cacher-histoire&id=<?= $book['id_cover'] ?>"
                                                               class="btn btn-primary px-2 mt-auto">
                                                                <i class="bi bi-eye-slash"></i> Cacher l'histoire</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6  px-2 ">
                                            <a href="index.php?action=info-histoire&id=<?= $book['id_cover'] ?>"
                                               class="btn btn-light btn-block ">
                                                <i class="bi bi-graph-up"></i> Stats</a>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#modalDelete<?= $book['id_cover'] ?>">
                                                Supprimer
                                            </button>
                                            <div class="modal fade" id="modalDelete<?= $book['id_cover'] ?>"
                                                 tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLongTitle">Supprimer une
                                                                histoire</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Etes-vous sûr de vouloir supprimer cette histoire ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Fermer
                                                            </button>
                                                            <form name="delete_cover" method="POST">
                                                                <a href="index.php?action=supprimer-livre&id=<?= $book['id_cover'] ?>"
                                                                   class="btn btn-danger btn-block my-2 px-2">Supprimer</a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } else if ($book['status'] == 1) { ?>
                                    <div>
                                        <div>
                                            <button type="button" class="btn px-2  btn-secondary btn-block mb-2"
                                                    data-toggle="modal" data-target="#modal<?= $book['id_cover'] ?>">
                                                <i class="bi bi-eye-slash"></i> Non publié
                                            </button>
                                            <div class="modal fade" id="modal<?= $book['id_cover'] ?>" tabindex="-1"
                                                 role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                 aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Publier
                                                                une histoire</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Etes-vous sûr de vouloir publier cette histoire ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Fermer
                                                            </button>
                                                            <a href="index.php?action=publier-histoire&id=<?= $book['id_cover'] ?>"
                                                               class="btn btn-primary px-2 mt-auto">
                                                                <i class="bi bi-eye"></i> Publier l'histoire</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">

                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#modalDelete<?= $book['id_cover'] ?>">
                                                Supprimer
                                            </button>
                                            <div class="modal fade" id="modalDelete<?= $book['id_cover'] ?>"
                                                 tabindex="-1"
                                                 role="dialog"
                                                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLongTitle">Supprimer l'histoire</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Etes-vous sûr de vouloir supprimer cette histoire ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Fermer
                                                            </button>
                                                            <form name="delete_cover" method="POST">
                                                                <a href="index.php?action=supprimer-livre&id=<?= $book['id_cover'] ?>"
                                                                   class="btn btn-danger btn-block my-2 px-2">Supprimer</a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }
    } ?>
    </div>
<?php } ?>
    </div>

<?php $content = ob_get_clean();
require('base.php'); ?>