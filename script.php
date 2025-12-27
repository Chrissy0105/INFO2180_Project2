<?php
header("Content-type: application/javascript");
session_start();
?>

	$('#homeView').on('click',function(e){
		e.preventDefault();
		let url = $("#homeView").attr("href");
		alert(url);
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	