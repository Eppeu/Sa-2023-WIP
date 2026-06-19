<!-- 
footer.php - Footer PopCo 
Ce fichier sert a être réutiliser sur toute les pages de PopCo ayant un footer
afin de gagner de la visibilité de de l'optimisation au niveau du code.
-->

<?php
echo 
"
<div class='row g-1 d-flex align-items-center'>
    <div class='col-4 fs-2 ps-4'>
        <a href='' target='_blank' class='text-decoration-none link-ctm-terciary-color-subtle'>
            <i class='fab fa-instagram bootstrap_nav_item_color'></i>
        </a>
        <a href='' target='_blank' class='text-decoration-none link-ctm-terciary-color-subtle'>
            <i class='fab fa-facebook bootstrap_nav_item_color'></i>
        </a>
        <a href='' target='_blank' class='text-decoration-none link-ctm-terciary-color-subtle'>
            <i class='fab fa-discord bootstrap_nav_item_color'></i>
        </a>
        
    </div>
    <div class='col-4 text-center'>
        <img src='../assets/icons/PopCo_logo.png' alt='Logo PopCo - Accueil' width='80' height='80'>
    </div>
    <div class='col-4 py-3 text-start d-lg-block text-end pe-4'>
        <a class='text-decoration-none link-ctm-terciary-color-subtle' data-bs-toggle='modal' href='#popco_ml' role='button'>
        Mentions légales
        </a>
        <div class='modal fade' id='popco_ml' tabindex='-1' aria-labelledby='popco_mlLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content bg-ctm-terciary-color'>
                <div class='modal-header'>
                    <h1 class='modal-title fs-5' id='popco_mlLabel'>MENTIONS LÉGALES</h1>
                    <button type='button' class='btn-close link-ctm-primary-color-subtle' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body text-center lh-sm'>
                    <p>
                        Conformément aux dispositions de la loi n° 2004-575 du 21 juin 2004 pour la confiance en l'économie numérique, il est précisé aux utilisateurs du site PopCo l'identité des différents intervenants dans le cadre de sa réalisation et de son suivi.
                    </p>
                    <h5>Edition du site</h5>
                    <p>
                        Le présent site, accessible à l’URL https://PopCo.fr (le « Site »), est édité par :<br>
                        Astrid CALAIS, résidant Tarbes 65000, de nationalité Française (France), né(e) le 20/10/2003,
                    </p>
                    <h5>Hébergement</h5>
                    <p>
                        Le Site est hébergé par la société IUT de Tarbes, situé 1 Rue Lautréamont, 65000 Tarbes, (contact téléphonique ou email : +33562444200).
                    </p>
                    <h5>Directeur de publication</h5>
                    <p>
                        Le Directeur de la publication du Site est Astrid CALAIS.
                    </p>
                    <h5>Nous contacter</h5>
                    <p>
                        Par téléphone : +33739393939<br>
                        Par email : astrid.migu@cfm.fr<br>
                        Par courrier : Tarbes 65000<br><br>
                        Génération des mentions légales par Legalstart.
                    </p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-ctm-secondary-color-subtle' data-bs-dismiss='modal'>Close</button>
                    <!-- bouton pour fermer les mentions légales 'Close'-->
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
";
?>