# TodoTxt Parser
A powerful PHP library for parsing and working with the
[todo.txt format](https://github.com/ginatrapani/todo.txt-cli/wiki/The-Todo.txt-Format).

---

## Parser
```
$input = "(A) Write the README file";

$parser = new Gravitask\Tools\Parser();
$task = $parser->parse($input);

$task->getPriority(); // Result: "A"
$task->getTask(); // Result: "Write the README file"
```

### Methods
#### parse($input)
Parse the provided `todo.txt` formatted `$input` and return a `Gravitask\TaskItem` object.

##### Example
```
$input = "(C) 2016-05-20 Go for a walk";

$task = $parser->parse($input);
```

---

## TaskItem
```
$todo = "(B) Improve README file @github";
$task = $parser->parse($todo);

$task->getPriority()  // "B"
$task->getTask();     // "Improve README file @github"
$task->getContexts(); // ['github']
```

### Constants
| Name               | Definition |
| ------------------ | ---------- |
| `STATUS_ACTIVE`    | The status of the task is active - i.e. in progress, **not** completed. |
| `STATUS_COMPLETED` | The status of the task is completed. |

### Methods
#### setTask($task)
Set the name/description of the task to be completed.

##### Example
```
$taskItem->setTask("Make another coffee");
```

---

#### getTask()
Retrieve the name/description of the task.

##### Example
```
$task->getTask();
// "Make another coffee"
```

---

#### setContexts(array $contexts)
Set the task's contexts to the items provided in the `$contexts` array.

##### Example
```
$contexts = ['email', 'computer'];
$task->setContexts($contexts);
```

---

#### addContext($context)
Append a single context item to the pre-existing array of contexts.

##### Example
```
$contexts = ['email'];
$task->setContexts($contexts);

$task->addContext('computer');
```

---

#### getContexts()
Retrieve an array of the task's contexts.

##### Example
```
$contexts = ['email', 'computer'];
$task->setContexts($contexts);

$task->getContexts();
// ['email', 'computer']
```

---

#### setProjects(array $projects)
Set the task's projects to the items provided in the `$projects` array.

##### Example
```
$projects = ['SecretProject'];
$task->setProjects($projects);
```

---

#### addProject($project)
Append a single project item to the pre-existing array of projects.

##### Example
```
$projects = ['SecretProject'];
$task->setProjects($projects);

$task->addProject('Work');
```

---

#### getProjects()
Retrieve an array of the task's projects.

##### Example
```
$projects = ['SecretProject'];

$task->setProjects($projects);
$task->addProject('Work');

$task->getProjects();
// ['SecretProject', 'Work']
```

---

#### setCreationDate($date)
Set the task's *optional* creation date.

The date **MUST** be formatted as `YYYY-MM-DD`.

##### Example
```
$task->setCreationDate('2016-06-21');
```

---

#### getCreationDate()
Retrieve the *optional* creation date value for the task.

##### Example
```
$task->getCreationDate();
// "2016-06-21"
```

---

#### setCompletionDate($date)
Set the date of when the task was completed.

**Requirements**:
* The date **MUST** be formatted as `YYYY-MM-DD`.
* The status **MUST** also be set to `STATUS_COMPLETED` via the `setStatus` method.

```
$task->setStatus(TaskItem::STATUS_COMPLETED);
$task->setCompletionDate('2016-06-22');
```

---

#### getCompletionDate()
Retrieve the date that the task was completed.

##### Example
```
$task->setStatus(TaskItem::STATUS_COMPLETED);
$task->setCompletionDate('2016-06-22');

$task->getCompletionDate()
// "2016-06-22"
```

---

#### setPriority($priority)
Set the task's priority to the provided uppercase single letter of the alphabet. `A`
signifies the highest priority, whilst `Z` represents the lowest.

##### Example
```
$task->setPriority("B");
```

---

#### getPriority()
Retrieve the task's priority value represented by a single, uppercase letter of the
alphabet.

##### Example
```
$task->setPriority("F");

$task->getPriority();
// "F"
```

---

#### setStatus($status)
Set the status of the task to a different value.

**Requirements**:
* You should **ONLY** use the values provided as `TaskItem` constants beginning with
  `STATUS_`.

##### Example
```
$task->setStatus(TaskItem::STATUS_COMPLETED);
```

---

#### getStatus()
Retrieve the current status of the task. By default this value will be
`TaskItem::STATUS_ACTIVE`.

##### Example
```
$task->setStatus(TaskItem::STATUS_COMPLETED);

$task->getStatus();
// Integer value representation of the status ENUM.
```

---

## Formatter
### Methods
#### format(TaskItem $taskItem)
Format the provided `TaskItem` using the preferred formatter class, e.g. `TodoTxtFormatter`.

##### Example
```
$task->setPriority("A");
$task->setTask("Write example code");

$formatter = new Gravitask\Formatter\TodoTxtFormatter();

$output = $formatter->format($task);
// "(A) Write example code"
```