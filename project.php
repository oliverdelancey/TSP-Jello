<!DOCTYPE HTML>
<html>
<head>
<style>
/**
 * This is all just style stuffs dont worry about it.
*/
#droppable {
  width: 350px;
  height: 300px;
  padding: 10px;
  border: 1px solid #aaaaaa;
}

.element {
  width: auto;
  height: auto;
  padding: 5px;
  border: 3px solid #454545;
}

.columnMain {
  float: left;
  width: auto;
  padding: 20px;
  height: 500px;
}

.columnGrab {
  float: left;
  width: 100%;
  padding: 20px;
  height: 500px;
}

.deleteButton {
  float: right;
  bottom: 0px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

</style>
<script>

/**
 * This function allows the element with this attribute to have items dropped on them.
*/
function allowDrop(ev) {
  ev.preventDefault();
}

/**
 * This function is added as an attribute which allows the element it is on to be actively
 * dragged to other locations in the page.
*/
function drag(ev) {
  ev.dataTransfer.setData("target", ev.target.id);
}

/**
 * This function is added as an attribute which allows the element to receive dragged items.
 * This means that items dragged onto this can actualy be put inside of it.
*/
function drop(ev) {
  ev.preventDefault();
  var data = ev.dataTransfer.getData("target");
  ev.target.appendChild(document.getElementById(data)); //appending the child aka inserting the element to the desired location.
  //to access the values stored in the element use some variation of this.
  //document.getElementById(data).querySelector("p").innerText
  //Update database according to change.
}

var taskContainer;      //mutable element with a global scope to allow accessiblity in functions.
var taskTextArea;;      // ^
var taskText;           // ^

var taskCounter = 0;    //allows for the creation of unique tasks each time.
var columnCounter = 0;  //allows for the creation of unique columns each time.
var dragArea;           //where all new tasks start at, note that this can just be the first column in an actual implementation.

var editArea = document.createElement("div");   //The div housing the edit text.
var editText = document.createElement("input"); //The textbox that will allow the user to modify tasks.
editText.setAttribute("type", "text");

/**
 * This will create the task element in the HTML and place it in the designated div.
 * NOTE: This takes information from a text field in order to generate the name of
 * the task. Additionally it requires some sort of way to produce a unique id each
 * time it is called, this only has to be per project though, not through the whole
 * database.
*/
function makeTask() {
                                                          //NOTE: below all of these \/ is just setting the various attribute for the respective elements.
  const taskContainer = document.createElement("div");    //overaching task container, the main div that hold the entire task.
  taskContainer.setAttribute("class", "element");
  taskContainer.setAttribute("id", "taskContainer" + taskCounter);
  taskContainer.setAttribute("draggable", "true");
  taskContainer.setAttribute("ondragstart", "drag(event)");

  const taskTextArea = document.createElement("div");     //The div housing the text of the task.
  taskTextArea.setAttribute("id", "taskTextArea" + taskCounter);

  const taskText = document.createElement("p");           //The actual text in the task.
  taskText.setAttribute("id", "taskText" + taskCounter);

  const editButton = document.createElement("button");    //The edit button for the task.
  editButton.setAttribute("id", "edit" + taskCounter);
  editButton.setAttribute("onclick", "editTask(this)");
  editButton.innerText = "Edit";
  const deleteButton = document.createElement("button");  //The delete button for the task.
  deleteButton.setAttribute("id", "deleteTask" + taskCounter);
  deleteButton.setAttribute("onclick", "deleteTask(this)");
  deleteButton.setAttribute("class", "deleteButton");
  deleteButton.innerText = "Delete";

  taskText.innerText = document.getElementById("taskName").value; //Setting the task name (what shows to the user) to whatever was inputted into the task name field.
                                            //Note that CSS styling was used to make this appear not ugly.
                                            //Assembling the task now that the elements have been created.
  dragArea.appendChild(taskContainer);      //Put the task container in the starting location.
  taskContainer.appendChild(taskTextArea);  //Put the Text Area div in the container.
  taskTextArea.appendChild(taskText);       //Put the actual text into the Text Area.
  taskContainer.appendChild(editButton);    //Put the edit button into the container.
  taskContainer.appendChild(deleteButton);  //Put the delete button into the container.

  document.getElementById("taskName").value = ""; //Clear task name input for convenience.
  taskCounter++;                                  //increment the unique task id counter.

  //Update database with new task.
  //All of the values needted to be updated can later be saved in vars before making all of the elements.
}

/**
 * This function is responsible for the creation of new columns which can have tasks droppedo into them.
 * NOTE: This takes information from a text field in order to generate the name of
 * the column. Additionally it requires some sort of way to produce a unique id each
 * time it is called, this only has to be per project though, not through the whole
 * database.
*/
function makeColumn() {
  var curr = document.getElementById("columnRow");                //grab the page element that houses all the columns.

                                                                  //Same as with tasks, below all of these are just the attribute assignments.
  const column = document.createElement("div");                   //Create the main column div.
  column.setAttribute("class", "columnMain");
  column.setAttribute("id", "droppable");
  column.setAttribute("ondrop", "drop(event)");
  column.setAttribute("ondragover", "allowDrop(event)");
  column.innerText = document.getElementById("columnName").value; //Set the name of the column to what the user put into the input field
  curr.appendChild(column);                                       //Add the column to the element housing all columns

  const deleteButton = document.createElement("button");          //Create the delete button for the column.
  deleteButton.setAttribute("id", "deleteColumn" + columnCounter);
  deleteButton.setAttribute("onclick", "deleteColumn(this)");
  deleteButton.setAttribute("class", "deleteButton");
  deleteButton.innerText = "Delete";
  column.appendChild(deleteButton);                               //Add delete button to the column.

  document.getElementById("columnName").value = "";               //reset the input field for convenience.
  columnCounter++;                                                //increment unique column id generator
}

/**
 * This function will delete the parent element of whatever element it is called on effectively deleting itself.
*/
function deleteColumn(event) {
  event.parentNode.parentNode.removeChild(event.parentNode);
}

/**
 * This function will allow the user to edit a task.
*/
function editTask(event) {
  if (event.innerText == "Edit") {          //If the button is clicked when it says "Edit".
    event.innerText = "Confirm";            //Change the button text to "Confirm".
    event.parentNode.appendChild(editArea); //Move the edit area to right under the button so the user can enter the new name.
    editArea.appendChild(editText);         //Move the actual text box to this new div.
  } else {                                  //Else meaning they are confirming their changes.
    event.innerText = "Edit";               //Change the text back to "Edit".
    document.getElementById("taskText" + event.id.replace(/^\D+/g, '')).innerText = editText.value;
    /**This comment is for the line above
     * This line is taking the id of the edit button itself and the removing anything
     * that is not an integer from the string. It is then prepending it with "taskText"
     * this effectively creates the id for the parent task since that element is not 
     * directly accessible from the edit button element.
     * Using this it is able to grab the taskText element and assign the text within
     * it to the editText that the user has entered.
    */
    editText.value = "";                    //Clear the edit text so it is not saved the next time the user edits.
    event.parentNode.removeChild(editArea); //Remove the edit area from the task.
  }
}

/**
 * This function deletes the task that it is called on.
*/
function deleteTask(event) {
  event.parentNode.parentNode.removeChild(event.parentNode);
}
</script>
</head>
<body>

<h2>Project: <?php echo $_GET["id"]?></h2>
<div class="row" id="columnRow">
  <button id="createColumn" onclick="makeColumn();">Create Column</button>
  <input type="text" id="columnName">
  <br>
  <?php 
    include "db.php";
    $columns = get_columns($_GET["id"]);
    for($i = 0; $i < sizeof($columns); $i++){
        $colid = $columns[$i][0];
        $colname = $columns[$i][1];
        echo "<script>var curr = document.getElementById('columnRow');                

        const column = document.createElement('div');                   
        column.setAttribute('class', 'columnMain');
        column.setAttribute('id', 'droppable');
        column.setAttribute('ondrop', 'drop(event)');
        column.setAttribute('ondragover', 'allowDrop(event)');
        column.innerText = $colname; //Set the name of the column to what the user put into the input field
        curr.appendChild(column);                                       //Add the column to the element housing all columns

        const deleteButton = document.createElement('button');          //Create the delete button for the column.
        deleteButton.setAttribute('id', $colid);
        deleteButton.setAttribute('onclick', 'deleteColumn(this)');
        deleteButton.setAttribute('class', 'deleteButton');
        deleteButton.innerText = 'Delete';
        column.appendChild(deleteButton);                               

        document.getElementById('columnName').value = '';               
        </script>";                                               
    }
  ?>
</div>
<br>
<h3>Make your tasks here and drag them into the appropriate column</h3>
<button id="createTask" onclick="makeTask();">Create Task</button>
<input type="text" id="taskName">
<br>
<div class="row" id="grabArea">
  <script>
    dragArea = document.createElement("div");     //Just the creation of the drag area, this will be how the look of the default column will be.
    dragArea.setAttribute("class", "columnGrab");
    dragArea.setAttribute("id", "droppable");
    dragArea.setAttribute("ondrop", "drop(event)");
    dragArea.setAttribute("ondragover", "allowDrop(event)");
    document.getElementById("grabArea").appendChild(dragArea);
  </script>
</div>
</body>
</html>