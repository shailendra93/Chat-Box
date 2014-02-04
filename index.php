<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chat-Box</title>
<link href="cb-css/chb-css.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-min.js" type="text/javascript"></script>
<script>
  $(document).ready(function(e) {
    $("#exit").click(function()
	   {
		var exit = confirm("Are you sure you want to end the session?");  
        if(exit==true){window.location = 'index.php?logout=true';}        
		})
		$("#submitmsg").click(function(){     
    var clientmsg = $("#msg").val();  
    $.post("post.php", {text: clientmsg});                
    $("#msg").attr("value", "");  
    return false;  
});  
     function loadLog(){       
  
        $.ajax({  
            url: "log.html",  
            cache: false,  
            success: function(html){          
                $("#msgBox").html(html); //Insert chat log into the #msBox div                 
            },  
        });  
    }     
       function loadLog(){       
    var oldscrollHeight = $("#msgBox").attr("scrollHeight") - 20; //Scroll height before the request  
    $.ajax({  
        url: "log.html",  
        cache: false,  
        success: function(html){          
            $("#msgBox").html(html); //Insert chat log into the #msgBox div     
              
            //Auto-scroll             
            var newscrollHeight = $("#msgBox").attr("scrollHeight") - 20; //Scroll height after the request  
            if(newscrollHeight > oldscrollHeight){  
                $("#msgBox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div  
            }                 
        },  
    });  
}  
    setInterval (loadLog, 2500);
});
  
</script>
</head>
<body>

<?php
session_start();  
function loginForm()
{  
    echo '
	  <div id="loginform">
  <form action="index.php" method="post">
      <p>Please enter your name to continue:</p> 
     <input type="text" name="name" id="name" />
     <input type="submit" value="submit" name="submit" />
  </form>
</div> ';
}
if(isset($_POST['submit']))
{
	if($_POST['name']!="")
	{
		$_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name'])); 
	}
	else
	{
		echo '<span class="error">Please type in a name</span>';  
	}
}
 
 if(!isset($_SESSION['name']))
 {
	 loginForm();
 }
else
{
?>
<div id="chatBox">
  <div id="top">
   <p> <b><?php echo $_SESSION['name']; ?></b></p>
   <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
  </div>
  <div style="clear:both"></div>
     <div id="msgBox">
     <?php  
      if(file_exists("log.html") && filesize("log.html") > 0){  
       $handle = fopen("log.html", "r");  
      $contents = fread($handle, filesize("log.html"));  
      fclose($handle);  
      
    echo $contents;  
     }  
     ?>
    </div>  
  </div>
    
    <form method="post" action="">
     <input type="text" name="msg" id="msg" />
     <input type="submit" value="send" id="submitmsg"/>
    </form>
    
  
</div>
<?php
}
if(isset($_GET['logout'])){   
      
    //Simple exit message  
    $fp = fopen("log.html", 'a');  
    fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");  
    fclose($fp);  
      
    session_destroy();  
    header("Location: index.php"); //Redirect the user  
}  
?>
</body>
</html>
   
