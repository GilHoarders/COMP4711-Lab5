<?php
class Tasks extends MY_Model {
  public function __construct() {
    parent::__construct('tasks', 'id');
  }

  function getCategorizedTasks() {
    foreach($this->all() as $task) {
      if($task->status != 2)
        $undone[] = $task;
    }

    foreach ($undone as $task)
      $task->group = $this->groups->get($task->group)->name;

    usort($undone, "orderByCategory");

    foreach ($undone as $task)
      $converted[] = (array) $task;

    return $converted;
  }
    // provide form validation rules
  public function rules()
  {
      $config = array(
          ['field' => 'task', 'label' => 'TODO task', 'rules' => 'alpha_numeric_spaces|max_length[64]'],
          ['field' => 'priority', 'label' => 'Priority', 'rules' => 'integer|less_than[4]'],
          ['field' => 'size', 'label' => 'Task size', 'rules' => 'integer|less_than[4]'],
          ['field' => 'group', 'label' => 'Task group', 'rules' => 'integer|less_than[5]'],
      );
      return $config;
  }
}

function orderByCategory($a, $b) {
  if($a->group < $b->group)
    return -1;
  elseif ($a->group > $b->group)
    return 1;
  else
    return 0;
}
