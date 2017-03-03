<?php

class Mtce extends Application {
  public function index() {
    $role = $this->session->userdata('userrole');
    $this->data['pagetitle'] = 'TODO List Maintenance ('. $role . ')';
    $tasks = $this->tasks->all();

    // substitute the status name
    foreach ($tasks as $task)
      if (!empty($task->status))
        $task->status = $this->statuses->get($task->status)->name;

    // build the task presentation output
    $result = '';
    foreach ($tasks as $task)
      $result .= $this->parser->parse('oneitem',(array)$task,true);

    // and then pass them on
    $this->data['display_tasks'] = $result;
    $this->data['pagebody'] = 'itemlist';
    $this->render();
  }
}
