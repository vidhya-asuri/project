  // js/collections/todos.js

  var app = app || {};


// Todo Collection
// ---------------

// The collection of todos is backed by *localStorage* instead of a remote
// server.
var PO = Backbone.Model.extend({
	defaults:{
		  name: 'default name',
		  date: '1/1/2000',
		  email: 'test@sstire.com',
		  location: 1,
		  amount: 0.0,
		  status: 1,
		  vendor: 'default vendor',
		  description: 'default description'
	},
	url:"/pos"	
});

var POs = Backbone.Collection.extend({
	model: PO,
	url: "/pos"
});

var poModel = new PO;
var poCollection = new POs;
// Get model with ID = 11 from database
// read → GET   /collection[/id]

/*poCollection.fetch({
    success: function(collection, response, options) 
    {
        alert(JSON.stringify(response));
    },
    error: function(collection, response, options)
    {
        alert(JSON.stringify(response));     
    }
});*/
// collection.sync(method, collection, [options])

// View for table listing collection of purchase orders
var POsView = Backbone.View.extend({
	el: '#purchaseOrdersListDiv',
	initialize: function(){
    	// Fetch all the purchase orders from database and display in the
    	// purchase orders table.
    	var pos = new POs;
    	pos.fetch({
		        success: function(collection, response, options) 
		        {
		            // alert(JSON.stringify(response));
		        	  var purchaseOrdersTable = new Tabulator("#purchaseOrdersList", {
		      		  	height:'90vh', 
		      		  	layout:"fitDataFill",
		      		    rowClick:function(e, row){
		      		    },
		      			columns:[ 
		      				    {title:"ID ", field:"po_id" , headerFilter:"input"},
		      				    {title:"Po Number", field:"po_num",  headerFilter:"input"},
		      				    {title:"Date", field:"date", headerFilter:"input" },
		      				    {title:"Name", field:"name"},
		      				    {title:"Email", field:"email"},
		      				    {title:"Vendor", field:"vendor", headerFilter:"input"},
		      				    {title:"Description", field:"description"},
		      				    {title:"Amount ", field:"amount"},
		      				    {title:"Purchase location", field:"purchase_location"},
		      				    {title:"Status", field:"status"}]
		        	  });	 
		          	  
		        	  purchaseOrdersTable.setData(response);
		        },
		        error: function(collection, response, options)
		        {
		            alert(JSON.stringify(response));     
		        }
       });
	}
});

    	
var posView = new POsView;

var POView = Backbone.View.extend({
	el: '#addPOFormDiv',
    events: 
    {
    	"submit #addPOForm" : "addPO"     
    }, 
    initialize: function(){

    },
    addPO :  function(e)
    {  
        e.preventDefault();
    	console.log("POView::save function called ");
    	
        var name = $('#name').val();
        var email = $('#email').val();  
        var vendor = $('#vendor').val(); 
        var description = $('#description').val(); 
        var amount = $('#amount').val(); 
        var location = $('#location').val(); 

        var formData = {
            name : name, 
            email : email,  
            vendor : vendor,
            description : description,
            amount : amount,
            location : location
        };

        var po = new PO();              
        po.set('name', name);
        po.set('email', email);
        po.set('vendor', vendor);
        po.set('description', description);
        po.set('amount', amount);
        po.set('location', location);
        
        var pos = new POs();
        
        var newPurchaseOrder  = pos.create(po);
        console.log(newPurchaseOrder);
       
        // read (GET) /pos
        
/*        pos.fetch({
	        success: function(collection, response, options) 
	        {
	            alert(JSON.stringify(response));
	        },
	        error: function(collection, response, options)
	        {
	            alert(JSON.stringify(response));     
	        }
        });
*/        
        /*
        The default sync handler maps CRUD to REST like so:

        	create → POST   /collection
        	read → GET   /collection[/id]
        	update → PUT   /collection/id
        	patch → PATCH   /collection/id
        	delete → DELETE   /collection/id
        */
        
     
        
    }

});

var poView = new POView();

