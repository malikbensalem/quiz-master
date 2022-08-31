<?
if (loggedin()){
?>
<nav class="navbar navbar-expand-md navbar-dark sticky-top bg-dark mb-5">
    <a class="navbar-brand active" >Webbiskools</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample04">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="<?echo $baseURL?>questionnaire#">Questionnaires</a></li>
            <li class="nav-item"><a class="nav-link" href="<?echo $baseURL?>results#">Results</a></li>
            <?if ($_SESSION['user_level']>1){?><li class="nav-item"><a class="nav-link" href="<?echo $baseURL?>users#">Users</a></li><?}?>
        </ul>
        <a class="btn btn-outline-light btn-sm" href="<?echo $baseURL?>">Logout</a>
    </div>
</nav>

<?}?>