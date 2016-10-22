<?php
  require_once "assets/class/init.php";

  /// doesn't do anything at the moment
  //$errMessage = 'none';

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
      // echo "loaded new words";
    } else {
      // echo "noting loaded";
    }
  } else {
    /// do nothing....
  }

  /// get the array of words from the database
  $allWords = $database->getAll();

  /// print out array to check output from database
  // print_r($allWords);

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
    <div class="container">
      <div class="search">
        <h1>Search</h1>
        <input type="search" id="searchField" value="">
      </div>

      <div class="results">
        <h1>Results</h1>
        <table class="table"></table>
      </div>

      <div class="input">
        <h1>Input</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="text" name="welsh_word" class="form-control" id="welsh_word" placeholder="welsh">
          <input type="text" name="english_word" class="form-control" id="english_word" placeholder="english">
          <button type="submit" name="submit_button" class="btn btn-default white" value="submit">Submit</button>
        </form>
      </div>

    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/jquery/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      // array of the alphabet, used to create the headings
      alphabet = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];

      // begin once the page loads
      $(document).ready(function() {

        // Gets the all verses from the PHP JSON file sets it to products variable,
        // which is at the beginning of this file
        var $allWords = JSON.parse('<?php echo json_encode($allWords) ?>');

        // turns the $allWords object into an array so the words can be sorted
        var allWords = [];
        $.each($allWords , function( index, value ) {
          allWords[index] = {id:index, welsh_word:value.welsh_word, english_word:value.english_word};
          // console.log($allWords[index]);
        });

        // Sorts the array by welsh_word
        allWords.sort(function(a, b){
            if(a.welsh_word < b.welsh_word) return -1;
            if(a.welsh_word > b.welsh_word) return 1;
            return 0;
        });

        // outputs all the words in the database into the results div
        var Sparent = $('.results');
        outputResultsTable(alphabet,allWords,Sparent, "all");

        // Search while typing. Each keystroke in the search bar triggers
        // the function. The string from the search bar is used to search
        // the allwords array. results are then formatted and appended to results.
        // if the search bar is empty all the words output to the results div
      	$('#searchField').bind('keyup', function () {
      		var lookup = $('#searchField').val();
          lookup = lookup.toLowerCase();
      		if (lookup!==''&&lookup.length>=1) {
      			get(alphabet, lookup, allWords);
      		} else {
            $('.results table').slice(1).remove();
            var Sparent = $('.results');
            outputResultsTable(alphabet,allWords,Sparent, "all")
      		}
      	});

        // get fundtion used in the 'keyup' action
        // children of results are removed first
        function get(alphabet,lookup, allWords ) {
          // console.log(lookup);
          $('.results table').slice(1).remove();
          var Sparent = $('.results');

          result = searchFor(lookup, allWords);
          outputResultsTable(alphabet,result,Sparent, "results");
        }

        // loops through the array to find the matching values
        function searchFor(toSearch, objects) {
          var result = [];
          toSearch = trimString(toSearch); // trim it
          for(var i=0; i<objects.length; i++) {
            for(var key in objects[i]) {
              if(objects[i][key].toLowerCase().indexOf(toSearch)!=-1) {
                if(!itemExists(result, objects[i])) result.push(objects[i]);
              }
            }
          }
          return result;
        }

        // the 3 functions trim code, code to ensure no duplicates in result set
        function trimString(s) {
          var l=0, r=s.length -1;
          while(l < s.length && s[l] == ' ') l++;
          while(r > l && s[r] == ' ') r-=1;
          return s.substring(l, r+1);
        }
        function compareObjects(o1, o2) {
          var k = '';
          for(k in o1) if(o1[k] != o2[k]) return false;
          for(k in o2) if(o1[k] != o2[k]) return false;
          return true;
        }
        function itemExists(haystack, needle) {
          for(var i=0; i<haystack.length; i++) if(compareObjects(haystack[i], needle)) return true;
          return false;
        }

        // result = searchFor(lookup, allWords);
        // console.log(result);

        // outputs the results into a table
        function outputResultsTable(alphabet_array, wordsArray, parent, check){
          $.each(alphabet_array, function(index, value){
            var theParent = parent;
            var $lastTable = theParent.children("table[class*='table']").last();
            var query = value;

            if (check == "results") {
              $lastTable.after('<table class="table_'+value+'"><tr><th></th><th></th><th></th></tr>');
            } else {
              $lastTable.after('<table class="table_'+value+'"><tr><th>'+value+'</th><th></th><th></th></tr>');
            }

            //$lastTable.after('<table class="table_'+value+'"><tr><th></th><th></th><th></th></tr>');
            //$lastTable.after('<table class="table_'+value+'"><tr><th>'+value+'</th><th></th><th></th></tr>');
            var $new_table = theParent.children("table[class='table_"+value+"']");

            for(var key in wordsArray) {
                if (wordsArray.hasOwnProperty(key)) {
                    var y = wordsArray[key].welsh_word.charAt(0)
                    y = y.toUpperCase();

                    if (y != query) {
                      // do nothing
                    } else {
                      $new_table.append('<tr><td>'+wordsArray[key].welsh_word+'</td><td><---></td><td>'+wordsArray[key].english_word+'</td></tr>');
                    }
                }
            }

            $new_table.append('</table>');
          }); // end of each loop
        } // end of outputResultsTable



      });
    </script>
  </body>
</html>
