function userList(exchangeId){
	$.ajax({
		type : "POST",
		url : "/Gifter/php/userList.php",
		data : "exchangeId=" + exchangeId,
		success : function(result) {
			var results = JSON.parse(result);
			var userListNode = $("#exchange"+exchangeId).find("ul");
			userListNode.empty();
			for (var i = 0; i < results.records.length; i++) {
				userListNode.append('<li><a href="main.php?context=' + results.records[i].userId + '">' + results.records[i].firstName + '\'s wishlist</a></li>');
			}
		}
	});
}

var menuLoad = function() {
	$.ajax({
		type : "POST",
		url : "/Gifter/php/exchangeList.php",
		success : function(result) {
			var results = JSON.parse(result);
			$("#userList").find("ul").empty();
			if (results.records.length === 0) {
				var emptyUserList = $('<li><a href="#" data-reveal-id="exchangeModal" class="alert-box">You aren\'t on an exchange. Click here to add one</a></li>');
					$("#userList").find("ul").append(emptyUserList);
					emptyUserList.click(function(){
						exchangeLoad();
					});
			} else {
				var exchangeListNode = $("#userList").find("ul");
				for (var i = 0; i < results.records.length; i++) {
					if(results.records[i].permission > 0){
						var exchangeNode = $('<li id="exchange'+results.records[i].exchangeId+'"class="has-dropdown not-click"><a href=#>'+results.records[i].exchangeName+'</a> <ul class="dropdown"></ul></li>');
						userList(results.records[i].exchangeId);
						exchangeListNode.append(exchangeNode);
					}
				}
			}
		}
	});
};

$(document).ready(function() {
	//call the function initially on pageLoad
	menuLoad();
});

//<li><a href="#">Dropdown Option</a></li>