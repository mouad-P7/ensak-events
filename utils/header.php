<header class="bgImage">
    <nav class="navbar">
        <div class="flex-between">
            <div class="navbar-header"><!--website name/title-->
                <?php 
                require_once 'utils/functions.php';
                echo '<a href = "index.php" class = "navbar-brand">
                    ENSAK EVENTS
                </a> ';
                ?>
            </div>
            <ul class="nav navbar-nav navbar-right"><!--navigation-->
            <div class="flex-between">
                <?php 
                //links to database contents. *if logged in
                if(is_logged_in()){
                    require_once 'utils/functions.php';
                    echo '<li><a href = "viewEvents.php">Your Events</a></li>';
                    // echo '<li><a href = "viewLocations.php">Locations</a></li>';
                    echo '<li class="btnlogout"><a class = "btn btn-default navbar-btn" href = "logout.php">Logout <span class = "glyphicon glyphicon-log-out"></span></a></li>';
                }  
                //links non database contents. *if logged out
                else {
                    echo '<a href="login.php" class="btn btn-default">
                        Login<Span class="glyphicon glyphicon-log-in"></span>
                    </a>';
                    // echo '<li><a href = "events2.php">Events</a></li>';
                    // echo '<li><a href = "locations2.php">Locations</a></li>';
                    // echo '<a href="login.php">Login <Span class="glyphicon glyphicon-log-in"></span></button>';
                }
                ?>
                </div>
            </ul>
        </div><!--container div-->
    </nav>
    <div class="col-md-12 header-content">
        <div class="container text-center">
            <h1>All Ensak Events</h1><!--jumbotron heading-->
            <p id="dateAndTime"></p>
        </div>
    </div>
</header>