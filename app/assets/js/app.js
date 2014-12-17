$(document).ready(function() {
	$('#checkout').click(function (e) {
		document.location.href = "checkout.php";
	})
});
$(document).ready(function () {
	$("#login").validate({
	    rules: {
	      "login[email]": {
	        required: true,
	        email: true
	      },
	      "login[password]": {
	        required: true
	      }
	    },
	    messages: {     
	      "login[email]": {
	        required: "Please enter the email address",
	        email: "Please enter valid email address"   
	      },
	     "login[password]": {
	        required: "Please enter the password"
	      }       
	    }
	}),
	$("#admin").validate({
	    rules: {
	      "admin[email]": {
	        required: true,
	        email: true
	      },
	      "admin[password]": {
	        required: true
	      }
	    },
	    messages: {     
	      "admin[email]": {
	        required: "Please enter the email address",
	        email: "Please enter valid email address"   
	      },
	     "admin[password]": {
	        required: "Please enter the password"
	      }       
	    }
	}),
	$("#signup").validate({
	    rules: {
	      "signup[username]": {
	        required: true
	        
	      },
	      "signup[email]": {
	        required: true,
	        email: true
	      },
	      "signup[password]":{
	      	required:true
	      }
	    },
	    messages: {     
	      "signup[username]": {
	        required: "Please enter the username",
	        email: "Please enter valid email address"   
	      },
	      "signup[email]":{
	      	required: "Please enter the email",
	        email: "Please enter valid email address"
	      },
	     "signup[password]": {
	        required: "Please enter the password"
	      }       
	    }
	}),
	$("#new_product").validate({
	    rules: {
	      "name": {
	        required: true
	        
	      },
	      "sub_name": {
	        required: true
	      },
	      "price":{
	      	required:true,
	      	number:true
	      },
	      "image[]":{
	      	required:true,
	      	file:true
	      }
	    },
	    messages: {     
		      "name": {
		        required: "Please enter product name",  
		      },
		      "sub_name":{
		      	required: "Please enter product sub_name"
		      },
		     "price": {
		        required: "Please enter product price"
		      },
		      "image[]": {
		        required: "Please upload product images"
		      }         
	    }
	}),
	$("#new_category").validate({
	    rules: {
	      "name": {
	        required: true
	        
	      },
	      "sub_name": {
	        required: true
	      },
	      "price":{
	      	required:true
	      }
	    },
	    messages: {     
	      "name": {
	        required: "Please enter category name",  
	      },
	      "sub_name":{
	      	required: "Please enter category sub_name"
	      },
	     "price": {
	        required: "Please enter category price"
	      }       
	    }
	});
});
