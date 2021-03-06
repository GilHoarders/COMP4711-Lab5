<?php

class Views extends Application {
  public function index() {
    $this->data['pagetitle'] = 'Ordered TODO list';
    $tasks = $this->tasks->all();
    $this->data['content'] = 'Ok';
    $this->data['leftside'] = $this->makePrioritizedPanel($tasks);
    $this->data['rightside'] = $this->makeCategorizedPanel($tasks);

    $this->render('template_secondary');
  }

  function makeCategorizedPanel($tasks) {
    $parms = ['display_tasks' => $this->tasks->getCategorizedTasks()];

    return $this->parser->parse('by_category', $parms, true);
  }

  function makePrioritizedPanel($tasks) {
    foreach ($tasks as $task) {
      if($task->status != 2)
        $undone[] = $task;
    }

    usort($undone, "orderByPriority");
    foreach ($undone as $task)
      $task->priority = $this->priorities->get($task->priority)->name;

    foreach ($undone as $task)
      $converted[] = (array) $task;

    $parms = ['display_tasks' => $converted];
    // INSERT the next two lines
    $role = $this->session->userdata('userrole');
    $parms['completer'] = ($role == ROLE_OWNER) ? '/views/complete' : '#';
    return $this->parser->parse('by_priority', $parms, true);
  }
    // complete flagged items
    function complete() {
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_OWNER) redirect('/work');

        // loop over the post fields, looking for flagged tasks
        foreach($this->input->post() as $key=>$value) {
            if (substr($key,0,4) == 'task') {
                // find the associated task
                $taskid = substr($key,4);
                $task = $this->tasks->get($taskid);
                $task->status = 2; // complete
                $this->tasks->update($task);
            }
        }
        $this->index();
    }
}

function orderByPriority($a, $b) {
  if ($a->priority > $b->priority)
    return -1;
  elseif ($a->priority < $b->priority)
    return 1;
  else
    return 0;
}

