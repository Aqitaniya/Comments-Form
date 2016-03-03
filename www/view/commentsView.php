<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comments Form</title>

    <!-- Bootstrap -->
    <link href="lib/bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<header>
    <div class="container">
        <div class="col-lg-offset-2 col-md-offset-2 page-title">Comments</div>
    </div>
</header>

<main>
    <div class="container">
        <div class="row">
        <div class="col-lg-offset-2 col-lg-8 col-md-offset-2  col-md-8 col-sm-12">
        <form enctype="multipart/form-data" method="post" class="form-horizontal" role="form" id="comment-form" action="#">
            <div class="form-group">
                <div class="col-sm-12">
                    <h1 class="form-name">Comments form</h1>
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email">
                </div>
            </div>
            <div class="form-group">
                <label for="inputFullname" class="col-sm-2 control-label">Full Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputFullname" name="fullname" placeholder="Username">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPhone" class="col-sm-2 control-label">Phone</label>
                <div class="col-sm-10">
                    <input type="tel" class="form-control" id="inputPhone" name="phone" placeholder="+XXXXXXXXXXXX">
                </div>
            </div>
            <div class="form-group">
                <label for="inputComment" class="col-sm-2 control-label">Comment</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="inputComment" name="comment" placeholder="Text input" rows="3"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputFile" class="col-sm-2 control-label">File input</label>
                <div class="col-sm-10">
                    <input type="file" id="inputFile" name="userfile">
                    <div class="input-group ">
                          <input type="text" class="form-control" id="inputFileName" name="filename" placeholder="The file is not selected" readonly>
                          <span class="input-group-btn trst2">
                            <button type="button" class="btn btn-default" id="inputBtnFile">Select file</button>
                          </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default" id="inputSubmit" name="commentSubmit" value="true">Add comment</button>
                </div>
            </div>
        </form>
        <section class="comments">
        <?php
            echo $comments->load_comments();
        ?>
        </section>
    </div>
    </div>
    </div>
</main>

<footer>
    <div class="container">
    </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="lib/bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/additional-methods.min.js"></script>
<script src="js/jquery.form.js"></script>
<script src="js/input.file.js"></script>
<script src="js/fields.validate.js"></script>
</body>
</html>