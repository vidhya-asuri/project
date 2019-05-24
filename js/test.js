  // js/collections/todos.js

  var app = app || {};


// Todo Collection
// ---------------

// The collection of todos is backed by *localStorage* instead of a remote
// server.

var Task = Backbone.Model.extend({
	defaults:{
		  name: 'learn backbone',
		  date: '1/1/2019'
	},
	url:"/tasks/new"
});


// Create our global collection of **Todos**.
var task = new Task();
console.log(task.get('name'));
console.log(task.get('date'));


var taskV = new Task({ name: "new task 2 May 24", date: '5/24/2019 ' });
taskV.save({}, {
    success: function (model, response, options) {
    	console.log("model");
    	console.log(model);
    	
        console.log("The model has been saved to the server");
    },
    error: function (model, xhr, options) {
        console.log("Something went wrong while saving the model");
    }
});


//var task1 = new Task();
//task1.set('name', 'task1 text');
//task1.set('date', '2/1/2019');
//console.log(task1.get('name'));
//console.log(task1.get('date'));
//
var Tasks = Backbone.Collection.extend({
	model: Task,
	url: "/tasks"
});
//
//
var tasksCollection = new Tasks();
//tasksCollection.add(task);
//tasksCollection.add(task1);
//
tasksCollection.fetch();

console.log("tasks in db");
tasksCollection.slice(0, tasksCollection.length ); 

  
  
  