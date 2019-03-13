<?php
//get tasklist array from POST
$task_list = filter_input(INPUT_POST, 'tasklist', 
        FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
if ($task_list === NULL) {
    $task_list = array();
    
//    add some hard-coded starting values to make testing easier
//    $task_list[] = 'Write chapter';
//    $task_list[] = 'Edit chapter';
//    $task_list[] = 'Proofread chapter';
}

//get action variable from POST
$action = filter_input(INPUT_POST, 'action');

//initialize error messages array
$errors = array();

//process
switch( $action ) {
    case 'Add Task':
        $new_task = filter_input(INPUT_POST, 'newtask');
        if (empty($new_task)) {
            $errors[] = 'The new task cannot be empty.';
        } else {
            //pushes new task to array
            array_push($task_list, $new_task);
        }
        break;
    case 'Delete Task':
        $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_index === NULL || $task_index === FALSE) {
            $errors[] = 'The task cannot be deleted.';
        } else {
            // delete current index
            unset($task_list[$task_index]);
            $task_list = array_values($task_list);
        }
        break;

    case 'Modify Task':
        $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_index === null || $task_index === false) {
            $errors[] = 'The task cannot be modified.';
        } else {
            // ** The form on task_list.php needed a $task_to_modify to be excecuted **
            //this sets the tasks to be modified to the selected index
            $task_to_modify = $task_list[$task_index];
        }
        break;
        
    case 'Save Changes':
        //modifiedId is declared to grab the selected index
        $modifiedId = filter_input(INPUT_POST, 'modifiedtaskid', FILTER_VALIDATE_INT);
        /**
         *  declared to set the content of the selected index 
         * to the user input on line 65
         */
        $modifiedTask = filter_input(INPUT_POST, 'modifiedtask');
        if ($modifiedId === null || $modifiedId === false) {
            $errors[] = 'The task cannot be modified.';
        } else {
            //sets the selected index's content to the user input content
            $task_list[$modifiedId] = $modifiedTask;
            $task_list = array_values($task_list);
            
        }
        break;
    case 'Cancel Changes':
    //do nothing, keep the task_list as it is
        $task_list = array_values($task_list);
        break;
    case 'Sort Tasks':
        if (empty($task_list)) {
            $errors[] = 'There is no task to sort!.';
        } else {
            //sorts array
            sort($task_list);
        }
        break;
    case 'Promote Task':
        $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_index === null || $task_index === false) {
            $errors[] = 'The task cannot be promoted.';
        } else {
            /**
             * Delcaring a variable for the current index
             * This is neccesary because line 98
             * changes the value of the current index completely
             * So this is needed to allow the index thats being "demoted"
             * to be changed to the "promoted" index
             */
            $task = $task_list[$task_index];
            // As long as the task isn't first
            if($task_index > 0){
            //setting the current index to itsself subtracted by 1
            $task_list[$task_index] = $task_list[$task_index - 1];
            //setting the index right before the current index to the current index
            $task_list[$task_index - 1] = $task;
            }
            else{
                $errors[] = "This task is already the number one priority!";
            }
            
            $task_list = array_values($task_list);
        }

        
/*
    case 'Modify Task':
    
    case 'Save Changes':
    
    case 'Cancel Changes':
    
    case 'Promote Task':
        
    case 'Sort Tasks':
    
*/
}

include('task_list.php');
?>