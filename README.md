#TaskList
## Server side
The server side is written in PHP without the use of frameworks. The MVC pattern was used.

For implement the routing was used [nikic/FastRoute](https://github.com/nikic/FastRoute)
##### Directory structure
```
|- app              
    |- config       
|- controller       
|- core             
|- libs            
|- model            
|- sql-dump         
|- public           
    |- css          
    |- fonts        
    |- images       
    |- js           
|- view             
    |- layouts      
    |- templetes    
```
## Frontend
Used on the frontend
* Bootdstrap
* JQuery
* JQuery-UI - to implement sorting tasks
* [DateTimePicker](https://xdsoft.net/jqplugins/datetimepicker/) - to implement deadline selection


## List of implemented requirements
#### Functional requirements
* *Ability to create/update/delete projects*
* *Ability to add tasks to my project*
* *Abilitye to update/delete tasks* 
* *Ability to prioritize tasks into a project*
* *Ability to choose deadline for my task*
* *Ability to mark a task as 'done'*
#### Additional functionality
* *It should work like one page WEB application and should use AJAX technology, load and submit data without reloading a page.*
* *It should have user authentication solution and a user should only have access to their own projects and tasks.*
