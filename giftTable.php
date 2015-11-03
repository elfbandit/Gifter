<?php
	//list out the various table options. Update these base options if the calls are ever changed
	$createAction = $server_path."/php/createAction.php";
	$updateAction = $server_path."/php/updateAction.php";
	$deleteAction = $server_path."/php/deleteAction.php";
	$listAction = $server_path."/php/listAction.php";
	$actions = array();
	
	//If the user viewing the table is not the owner, then disable the create, delete, and update options
	if($_SESSION['userInfo']['userId']==$_SESSION['context']['userId']){
		$actions['listAction'] = $listAction;
		$actions['createAction'] = $createAction;
		$actions['deleteAction'] = $deleteAction;
		$actions['updateAction'] = $updateAction;
	}else{
		$actions['listAction'] = $listAction;
	}
?>

<script type="text/javascript">
//DEFINING CUSTOM CHILD TABLES AND BUTTONS
//REFRESH ALL TABLES
var refreshTables = function(){
	$('#mainTableContainer').jtable("reload");
    $('#shoppingTableContainer').jtable("reload");
    $('#ThankYouTableContainer').jtable("reload");
};

//COMMENT TABLE
var commentTable = function (giftData) {
                        //Create an image that will be used to open child table
                        var $button = $('<img src="img/comment.png" title="show comments"/>');
                        //Open child table when user clicks the image
                        $button.click(function () {
                            $('#mainTableContainer').jtable('openChildTable',
                                    $button.closest('tr'),
                                    {
                                        title: giftData.record.name + ' - Comments',
                                        actions: {
                                            listAction: '/Gifter/php/comment.php?action=list&giftId='+giftData.record.giftId,
                                            createAction: '/Gifter/php/comment.php?action=create&giftId='+giftData.record.giftId
                                        },
                                        fields: {
                                            userId: {
                                                type: 'hidden',
                                            },
                                            commentId: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false,
                                                sort: true
                                            },
                                            name: {
                                                title: 'Name',
                                                width: '20%',
                                                create: false,
                                                edit: false
                                            },
                                            comment: {
                                                title: 'Comment',
                                                width: '70%',
                                                type: 'textarea'
                                            },
                                            time: {
                                                title: 'Time',
                                                width: '10%',
                                                type: 'text',
                                                create: false,
                                                edit: false,
                                            },
                                            deleteButton: {
                                            	width: '5%',
                                            	sorting : false,
												create : false,
												edit : false,
												list : true,
												display: function (data) {
													if (data.record.userId == userInfo.userId) {
           												$button=$('<button title="delete" class="jtable-command-button jtable-delete-command-button" />');
           												$button.click(function(){
           													$.ajax({
        		    											type:"POST",
        		    											url:"php/comment.php?action=delete&giftId=" + giftData.record.giftId,
        		    											data:"commentId="+data.record.commentId,
        		    											success:function(result){
        		    												//refresh the tables
    																refreshTables();
    																}
    															});
           												});
           												return $button;
                                            		}
                                            	}	
                                            }
                                        }
                                    }, function (data) { //opened handler
                                        data.childTable.jtable('load');
                                    });
                        });
                        //Return image to show on the person row
                        return $button;
                    };
//EMAIL BUTTON         
var emailButton = function (data) {
	$button = $('<img src="img/email.png" title="send an email"/>');
    $button.click(function(){
        window.location.href = "mailto:"+encodeURIComponent(data.record.email)+"?subject=Thank%20you%20for%20the%20gift&body="+encodeURIComponent("Thank you "+data.record.gifter+" for the "+data.record.name+"!");
 	});
 	
    return $button;
};

//THANK YOU COMPLETE BUTTON
var thankCompleteButton = function (data) {
	$button = $('<img src="img/checkmark.png" title="mark this complete"/>');
    $button.click(function(){
		$.ajax({
        	type:"POST",
        	url:"php/deleteAction.php?table=thank",
        	data:"giftId="+data.record.giftId,
        	success:function(result){
        		  //refresh the tables
    				refreshTables();
 				}
 			}); 	
 	});
 	
    return $button;
};


$(document).ready(function() {
    	
    //MAIN DISPLAY TABLE
	$('#mainTableContainer').jtable({
		title : context.firstName+'\'s Wishlist',
		deleteConfirmation : false,
		
		//selecting : true,
		//multiselect : true,
		//selectingCheckboxes : true,
		//selectOnRowClick :true,
		//use this to customize the default message
		messages: {
			serverCommunicationError: 'An error occured while communicating to the server.',
    		loadingMessage: 'Loading records...',
   			noDataAvailable: 'No data available!',
   			addNewRecord: 'Add to your list',
  			editRecord: 'Edit Record',
  			areYouSure: 'Are you sure?',
			deleteConfirmation: 'This gift will be deleted from your list. Are you sure?',
 			save: 'Save',
 			saving: 'Saving',
  			cancel: 'Cancel',
 			deleteText: 'Delete',
  			deleting: 'Deleting',
  			error: 'Error',
   			close: 'Close',
			cannotLoadOptionsFor: 'Can not load options for field {0}',
		   	pagingInfo: 'Showing {0}-{1} of {2}',
		    pageSizeChangeLabel: 'Row count',
		    gotoPageLabel: 'Go to page',
		    canNotDeletedRecords: 'Can not deleted {0} of {1} records!',
		    deleteProggress: 'Deleted {0} of {1} records, processing...'
		},
		actions :
			<?php echo json_encode($actions); ?>,
		fields : {
		giftId : {
				key : true,
				list : false
			},
			name : {
				title : 'Gift Name',
				width : '20%',
				sorting : true
			},
			link : {
				title : 'URL link',
				width : '10%',
				display: function (data) {
					if (data.record.link) {
						return $('<a href="'+data.record.link+'" target="_blank">click here</a>');
					}
				}
			},
			description : {
				title : 'Description',
				width : '40%',
				type : 'textarea'
			},
			value : {
				title : 'Value',
				width : '10%',
				sorting : true
			} <?php if($_SESSION['userInfo']['userId']!=$_SESSION['context']['userId']){
				 //only show gifter column if person viewing is NOT the current user ?>
			,gifter : {
				title : 'Gifter',
				width : '10%',
				sorting : false,
				create : false,
				edit : false,
				list : true,
				display: function (data) {
					 //Create an image that will be used to open child table
					
        			if (data.record.gifterId == userInfo.userId) {
           				//return $('<p>'+data.record.gifter+'</p>');
           				return $('<p>You have claimed this gift</p>');
      				} else if(data.record.gifterId){
      					return $('<p>Claimed</p>');
      				}else {
        		    	$claimButton = $('<div class="button tiny" name="claim">claim gift </div>');
        		    	$claimButton.click(function(){
        		    		$.ajax({
        		    			type:"POST",
        		    			url:"php/claimAction.php",
        		    			data:"giftId="+data.record.giftId,
        		    			success:function(result){
        		    				//refresh the tables
    								refreshTables();
 							 	}
 							 });
 						});
        		    }
        		    	return $claimButton;
     		  	}
     		  		
    		},//CHILD TABLE FOR COMMENTS"
                Comments: {
                    title: '',
                    width: '5%',
                    sorting: false,
                    edit: false,
                    create: false,
                    deleteConfirmation : false,
                    addRecordButton : $('<div class="button">Add a comment</div>'),
                    display: commentTable
                }
			<?php }?>
		}
	});

	$('#mainTableContainer').jtable('load');
});


//Begin Shopping table
<?php
$actions=array();
$actions['listAction'] = $listAction."?table=shop";
$actions['deleteAction'] = $deleteAction."?table=shop";
?>

    $(document).ready(function() {
	$('#shoppingTableContainer').jtable({
		title : 'Your shopping list',
		deleteConfirmation: function(data) {
    		data.deleteConfirmMessage = 'Are you sure to delete this from your list? This will allow someone else to claim it for themselves';
		},
		actions :
			<?php echo json_encode($actions); ?>
									,
		fields : {
		giftId : {
				key : true,
				list : false
			},
			recipient : {
				title : 'For',
				width : '10%',
			},
			name : {
				title : 'Gift Name',
				width : '20%',
				sorting : true
			},
			link : {
				title : 'URL link',
				width : '10%',
				display: function (data) {
					if (data.record.link) {
						return $('<a href="'+data.record.link+'" target="_blank">link</a>');
					}
				}
			},
			description : {
				title : 'Description',
				width : '45%',
				type : 'textarea'
			},
			value : {
				title : 'Value',
				width : '10%',
				sorting : true
			},
			Comments: {
                    title: '',
                    width: '5%',
                    sorting: false,
                    edit: false,
                    create: false,
                    deleteConfirmation : false,
                    addRecordButton : $('<div class="button">Add a comment</div>'),
                    display: commentTable
                }
     		  		
    		}
	});
	
		$('#shoppingTableContainer').jtable('load');
	});
	
//Begin Thank-you table
<?php
$actions=array();
$actions['listAction'] = $listAction."?table=thank";
?>

    $(document).ready(function() {
	$('#ThankYouTableContainer').jtable({
		title : 'Your Thank-You list',
		deleteConfirmation: function(data) {
    		data.deleteConfirmMessage = 'This will delete the reminder from your list';
		},
		
		actions :
			<?php echo json_encode($actions); ?>
									,
		fields : {
		giftId : {
				key : true,
				list : false
			},
			gifter : {
				title : 'From',
				width : '10%',
			},
			name : {
				title : 'Gift Name',
				width : '20%',
				sorting : true
			},
			link : {
				title : 'URL link',
				width : '10%',
				display: function (data) {
					if (data.record.link) {
						return $('<a href="'+data.record.link+'" target="_blank">link</a>');
					}
				}
			},
			description : {
				title : 'Description',
				width : '40%',
				type : 'textarea'
			},
			email : {
				title : 'Email',
				width : '10%',
				sorting : true
			},
			emailButton: {
                    title: '',
                    width: '5%',
                    sorting: false,
                    edit: false,
                    create: false,
                    deleteConfirmation : false,
                    display: emailButton
               },
            thanked : {
            	title: '',
            	width: '5%',
            	sorting : false,
            	edit: false,
            	create: false,
            	display : thankCompleteButton
            }
     		  		
    		}
	});
	
		$('#ThankYouTableContainer').jtable('load');
	});
   
</script>