<?php

  if (file_exists('assets/class/init.php')) {
    require_once "assets/class/init.php";
  } else {
    require_once "assets/class/init_pub.php";
  }


  /// doesn't do anything at the moment
  //$errMessage = 'none';

  /// get the array of words from the database
  $allWords = $database->getAll();

  /// print out array to check output from database
  // print_r($allWords);

  /// sorts the array into alphabetical order
  // foreach ($allWords as $key => $row) {
  //   $volume[$key]  = $row['welsh_word'];
  //   $edition[$key] = $row['english_word'];
  // }
  // array_multisort($volume, SORT_ASC, $edition, SORT_ASC, $allWords);



  /// if a psot has been submitted upload the the word to the database
  if ($_POST['submit_button'])
  {
    $welsh_word = $_POST['welsh_word'];
  	$english_word = $_POST['english_word'];
    // echo $welsh_word . " " . 	$english_word;
    $table = "word_pantri";
  	$input = array(
  		'welsh_word' => $welsh_word,
  		'english_word' => $english_word
  	);

    if($database->insert($input, $table))
    {
      echo "loaded new words";
    } else {
      echo "noting loaded";
    }
  } else {
    /// do nothing....
  }

?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>page title</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="search">
      <h1>Search</h1>
    </div>

    <div class="results">
      <h1>Results</h1>
      <div name="jam" class="output"></div>
      <table class="table"><table>
    </div>

    <div class="input">
      <h1>Input</h1>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="welsh_word" class="form-control" id="welsh_word" placeholder="welsh">
        <input type="text" name="english_word" class="form-control" id="english_word" placeholder="english">
        <button type="submit" name="submit_button" class="btn btn-default white" value="submit">Submit</button>
      </form>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/jquery/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      alphabet = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
      $(document).ready(function() {
        //Gets the all verses from the PHP JSON file sets it to products variable
        var $allWords = JSON.parse('<?php echo json_encode($allWords) ?>');

        var allWords = [];
        $.each($allWords , function( index, value ) {
          allWords[index] = {id:index, welsh_word:value.welsh_word, english_word:value.english_word};
          // console.log($allWords[index]);
        });

        //// sorts the array by welsh_word
        allWords.sort(function(a, b){
            if(a.welsh_word < b.welsh_word) return -1;
            if(a.welsh_word > b.welsh_word) return 1;
            return 0;
        });

        // console.log(allWords);
        //// print out the array
        // for(var key in allWords) {
        //     if (allWords.hasOwnProperty(key)) {
        //         $output.append(allWords[key].welsh_word);
        //         $output.append('  <->  ');
        //         $output.append(allWords[key].english_word);
        //         $output.append('<br>');
        //     }
        // }

        $.each(alphabet, function(index, value){
          var $parent = $('.results');
          var $lastTable = $parent.children("table[class*='table']").last();
          var query = value;
          $lastTable.after('<table class="table_'+value+'"><tr><th>'+value+'</th><th></th><th></th></tr>');
          var $new_table = $parent.children("table[class='table_"+value+"']");

          for(var key in allWords) {
              if (allWords.hasOwnProperty(key)) {
                  var y = allWords[key].welsh_word.charAt(0)
                  y = y.toUpperCase();

                  if (y != query) {
                    // do nothing
                  } else {
                    $new_table.append('<tr><td>'+allWords[key].welsh_word+'</td><td><---></td><td>'+allWords[key].english_word+'</td></tr>');
                  }
              }
          }
          $new_table.append('</table>');
        });

      });
    </script>
  </body>
</html>
