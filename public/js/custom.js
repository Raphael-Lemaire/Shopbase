var count = 3;

function onscroll(){
	if ($(window).scrollTop() >= ($(document).height() - $(window).height() - 200)) {
			count = count + 6;
			$.ajax({

			type: 'POST',
			cache: false,
			dataType: 'html',
			data: {id: count},
			url: '/ajax',
			success: function(data) {
				$('.ajax_container').html(data);
				initFunctions()
			}
		});
	}
}

function loadProducts() {
	$.ajax({
		type: 'POST',
		cache: false,
		dataType: 'html',
		data: {id: count},
		url: '/ajax',
		success: function(data) {
			
			$('.ajax_container').html(data);

			initFunctions()
		}	
	});
}

function addProductInCart(id) 
{
	$.ajax({
		type: 'POST',
		cache: false,
		dataType: 'html',
		data: {idProduct: id},
		url: '/ajax/saveProductInCart/' + id,
		success: function(data) 
		{	
			if(data==1) {
				alert('Produit sauvegardé');
			}
			if(data==2) {
				alert('Utilisateur non connecté');
			}
			loadsmallCarts();
			// initFunctions(); 
		}	
	});
}

function removeProductInCart(id) 
{
	$.ajax({
		type: 'POST',
		cache: false,
		dataType: 'html',
		data: {idProduct: id},
		url: '/ajax/removeProductInCart/' + id,
		success: function(data) 
		{	
			if(data==1) {
				alert('Le produit a bien été supprimé du panier !');
			}
			if(data==2) {
				alert('Utilisateur non connecté');
			}
			loadsmallCarts();
			loadCarts();
			//initFunctions(); 
		}	
	});
}

function loadCarts() {
	
$.ajax({
			type: 'POST',
			cache: false,
			dataType: 'html',
			data: {},
			url: '/ajax/cart',
			success: function(data) {
				var temp = document.getElementsByTagName("template")[0];
temp.innerHTML = myTemplate;
var clon = temp.content.cloneNode(true);
document.body.appendChild(clon);
initFunctions();
			}
		});
}

function loadsmallCarts() {
	$.ajax({
		type: 'POST',
		cache: false,
		dataType: 'html',
		data: {},
		url: '/ajax/smallcart',
		success: function(data) {
			
			$('.ajax_smallcart_container').html(data);
		}	
	});
}

function loadJs() {
	$.getScript('js/functions.js', function() {
  		console.log('Script function loaded.');
	});	
	
}
	

function initFunctions()
{
	$(".ajout-panier").on("click", function(){
        var dataId = $(this).attr("data-id");
		addProductInCart(dataId);
		return false;
    });
	
	$(".supprimer-produit-panier").on("click", function(){
        var dataId = $(this).attr("data-id");
		removeProductInCart(dataId);
		return false;
    });
	
	loadJs();
}



$( document ).ready(function() {
	
	loadsmallCarts();
	loadCarts();
	loadProducts();
	
	$( window ).scroll(function() {
  		onscroll();
	});
	
});

