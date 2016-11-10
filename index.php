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
      header("Location: success.php");
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
    <title>croeso i'r pantri</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom style sheet -->
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Abhaya+Libre" rel="stylesheet">

  </head>
  <body>
    <div class="search col-xs-12">
      <input type="search" id="searchField" value="" placeholder="search" class="focus">
      <img src="assets/media/icons/teal-cross-full.png" class="cross-icon-search cross-icon">
    </div>

    <div class="input col-xs-12">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="welsh_word" class="form-control" id="welsh_word" placeholder="welsh">
        <input type="text" name="english_word" class="form-control" id="english_word" placeholder="english">
        <button type="submit" name="submit_button" class="btn btn-default white" value="submit">Submit</button>
        <img src="assets/media/icons/teal-cross-full.png" class="cross-icon-input cross-icon">
      </form>
    </div>

    <div class="backcolor">
      <h1 class="pagetitle">Y Pantri Cymraeg</h1>
    </div>

    <div class="results">
      <div class="wrapper wrap wrap-start col-xs-12"></div>
    </div>

    <div class="rectangle">
        <img src="assets/media/icons/search_white.png" class="icon ">
        <div class="line"></div>
        <div class="add-icon">+</div>
        <div class="game-icon">g</div>
    </div>
    <div class="game-wrapper">
      <div class="btn-info btn start-game col-xs-10">New Word</div>
      <img src="assets/media/icons/teal-cross-full.png" class="cross-icon-game cross-icon cross-game">
      <h1 class="pagetitle">Y Pantri Cymraeg</h1>
      <h1 class="pagetitle">Word Match</h1>
      <!-- button to start the game  -->
      <div class="game-q questionBox">welsh word</div>
      <div class="game-a0 answerBox">engish word 1</div>
      <div class="game-a1 answerBox">engish word 2</div>
      <div class="game-a2 answerBox">engish word 3</div>
      <div class="game-a3 answerBox">engish word 4</div>
      <div class="btn-info btn start-game col-xs-10">New Word</div>
    </div>

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
            $('.results .wrap').slice(1).remove();
            $('.row').remove();
            var Sparent = $('.results');
            outputResultsTable(alphabet,allWords,Sparent, "all")
      		}
      	});

        // get fundtion used in the 'keyup' action
        // children of results are removed first
        function get(alphabet,lookup, allWords ) {
          // console.log(lookup);
          $('.results .wrap').slice(1).remove();
          $('.row').remove();
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

          if (check == "results") {
            var theParent = parent;
            var $lastTable = theParent.children("div[class*='wrapper']").last();
            $lastTable.after('<div class="wrapper_ col-xs-12 wrapSearch wrap">');

            $.each(alphabet_array, function(index, value){
              var query = value;
              for(var key in wordsArray) {
                  if (wordsArray.hasOwnProperty(key)) {
                      var y = wordsArray[key].welsh_word.charAt(0)
                      y = y.toUpperCase();

                      if (y != query) {
                        // do nothing
                      } else {
                        $lastTable.append('<div class="row col-xs-12"><div class="word welsh_word col-xs-5">'+wordsArray[key].welsh_word+'</div><div class="word english_word col-xs-5">'+wordsArray[key].english_word+'</div></div>');
                      }
                  }
              }


            });
            $lastTable.append('</div>');

          } else {
            $.each(alphabet_array, function(index, value){
              var theParent = parent;
              var $lastTable = theParent.children("div[class*='wrapper']").last();
              var query = value;

              $lastTable.after('<div class="wrapper_'+value+' col-xs-12 wrapMains wrap room"><div class="title col-xs-12">'+value+'</div>');

              var $new_table = theParent.children("div[class*='wrapper_"+value+"']");

              for(var key in wordsArray) {
                  if (wordsArray.hasOwnProperty(key)) {
                      var y = wordsArray[key].welsh_word.charAt(0)
                      y = y.toUpperCase();

                      if (y != query) {
                        // do nothing
                      } else {
                        $new_table.append('<div class="row col-xs-12"><div class="word welsh_word col-xs-5">'+wordsArray[key].welsh_word+'</div><div class="word english_word col-xs-5">'+wordsArray[key].english_word+'</div></div>');
                      }
                  }
              }

              $new_table.append('</div>');
            }); // end of each loop
          } // end of if else statement
        } // end of outputResultsTable


        $(window).scroll(function() {

            if ($(this).scrollTop()>580)
             {
                $('.rectangle').fadeOut();
             }
            else
             {
              $('.rectangle').fadeIn();
             }
         });

       $(".cross-icon-search").click(function(){
            $('.row').remove();
             $(".search").hide();
             $(".pagetitle").show();
             $('.search').children('input').val('');
             $('.results .wrap').slice(1).remove();
             var Sparent = $('.results');
             outputResultsTable(alphabet,allWords,Sparent, "all");
      });

      $(".cross-icon-game").click(function(){
            $(".game-wrapper").hide();
     });


      $(".cross-icon-input").click(function(){
            $(".input").hide();
     });

      $(".icon").click(function(){
            $(".search").show();
            $(".pagetitle").hide();
            $('.search').children('input').focus();
     });

      $(".add-icon").click(function(){
           $(".input").show();
           $('.input').children('input').focus();
      });

      $(".game-icon").click(function(){
        $(".input").hide();
        $(".search").hide();
        $(".game-wrapper").show();
      });


      // Game section
      $('.start-game ').bind('touchstart click', function(){
        $('.questionBox').css({'background-color': '#ffffff' , 'color':'#1e3746'});
        for (var i = 0; i < 4; i++) {
          // console.log('.game-a'+ i );
          $('.game-a'+ i ).removeClass('false');
          $('.game-a'+ i ).removeClass('correct');
          $('.game-a'+ i ).css({'background-color': '#ffffff' , 'color':'#1e3746'});
          $('.game-a'+ i ).off();
        }

        // get max number from allWords
        var maxNum = allWords.length -1;

        // get 4 unique random number no higher than maxNum
        var n = randomArray(4, maxNum);
        // console.log(n);

        // set create gameAnswers array which contains welsh and english words
        var gameAnswers = [];
        // console.log(allWords);
        // console.log(n);
        $.each(n , function( index, value ) {
          gameAnswers[index+1] = {welsh_word:allWords[value].welsh_word, english_word:allWords[value].english_word, type:"false"};
        });

        gameAnswers[1].type = "correct";
        console.log(gameAnswers);

        var o = randomArray(4, 4);
        $('.game-q').empty();
        $('.game-q').append(gameAnswers[1].welsh_word);

        $('.game-a0').empty();
        $('.game-a1').empty();
        $('.game-a2').empty();
        $('.game-a3').empty();

        for (var i = 0; i < o.length; i++) {
          // console.log('.game-a'+ i );
          $('.game-a'+ i ).append(gameAnswers[o[i]].english_word);
          $('.game-a'+ i ).addClass(gameAnswers[o[i]].type);
        }


        $('.false').on('click touchstart' , function(){
          $(this).css({'background-color': '#f05f5a' , 'color':'#FBFBFF'});
        });

        $('.correct').on('click touchstart' , function(){
          $(this).css({'background-color': '#64d7d7' , 'color':'#FBFBFF'});
          $('.game-q').css({'background-color': '#64d7d7' , 'color':'#FBFBFF'});
        });


        // on click for right / wrong answers
        // $(".false").click(function(){
        //      $(this).css({'background-color': '#f05f5a' , 'color':'#FBFBFF'});
        // });

        // $(".correct").click(function(){
        //      $(this).css({'background-color': '#64d7d7' , 'color':'#FBFBFF'});
        //      $('.game-q').css({'background-color': '#64d7d7' , 'color':'#FBFBFF'});
        // });

        // randomArray(4, maxNum);
        function randomArray(len, maxNum){
          var arr = []
          while(arr.length < len){
            var randomnumber = Math.ceil(Math.random()*maxNum)
            if(arr.indexOf(randomnumber) > -1) continue;
            arr[arr.length] = randomnumber;
          }
          return arr;
        }

      }); // end of the game function

    });

    </script>
    <script src="assets/js/slider.js"></script>
  </body>
</html>
