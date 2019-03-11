<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

/////////////////////////////////////////////////////////////////////////////////////////////
// CRUD
/////////////////////////////////////////////////////////////////////////////////////////////

// CREATE

$app->get('/create', function($request, $response, $args)
{
    // Sample log message
    $this->logger->info("Slim-Skeleton '/test/create' route");

    // Render index view
    return $this->renderer->render($response, 'create.phtml', $args);
});

$app->post('/todo/create', function ($request, $response) {
    $input = $request->getParsedBody();
    $sql = "INSERT INTO tasks (task) VALUES (:task)";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("task", $input['task']);
    $sth->execute();
    $input['id'] = $this->db->lastInsertId();
    return $this->response->withJson($input);
});


/////////////////////////////////////////////////////////////////////////////////////////////
// READ

// $app->get('/read', function($request, $response, $args)
// {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/test/create' route");
//
//     // Render index view
//     return $response->withRedirect($this->router->pathFor('routename', ['game' => 'bar']));
// });


$app->get('/read', function ($request, $response, $args) {
  $sth = $this->db->prepare("SELECT * FROM tasks ORDER BY task");
  $sth->execute();
  $todos = $sth->fetchAll();
  foreach($todos as $todo)
  {
      $info = "id: " . $todo['id'] . ", task: " . $todo['task'];
      $info .= ", status: " . $todo['status'] . ", created at: " . $todo['created_at'] . "<br>";
      echo $info;
  }
  // return $this->response->withJson($todos);
});

// Retrieve todo with id
$app->get('/todo_detail/[{id}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM tasks WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $todos = $sth->fetchObject();
    return $this->response->withJson($todos);
});

/////////////////////////////////////////////////////////////////////////////////////////////
// UPDATE

$app->get('/update', function($request, $response, $args)
{
    // Sample log message
    $this->logger->info("Slim-Skeleton '/test/update' route");

    // Render index view
    return $this->renderer->render($response, 'update.phtml', $args);
});

$app->post('/todo/update', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    $id = $request->getParsedBody()['id'];
    $task = $request->getParsedBody()['task'];
    $sth = $this->db->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->bindParam("task", $input['task']);
    $sth = $this->db->prepare("UPDATE tasks SET task='$task' WHERE id='$id'");
    $sth->execute();
    return $this->response->withJson($input);
});


/////////////////////////////////////////////////////////////////////////////////////////////
// DELETE

$app->get('/delete', function($request, $response, $args)
{
    // Sample log message
    $this->logger->info("Slim-Skeleton '/test/update' route");

    // Render index view
    return $this->renderer->render($response, 'delete.phtml', $args);
});

$app->post('/todo/delete', function ($request, $response, $args) {
    $id = $request->getParsedBody()['id'];
    $sth = $this->db->prepare($sql);
    $sth = $this->db->prepare("DELETE FROM tasks WHERE id='$id'");
    $sth->execute();
});


/////////////////////////////////////////////////////////////////////////////////////////////
//
/////////////////////////////////////////////////////////////////////////////////////////////


// Search for todo with given search teram in their name

$app->get('/todos/search/[{query}]', function ($request, $response, $args) {
    $sth = $this->db->prepare("SELECT * FROM tasks WHERE UPPER(task) LIKE :query ORDER BY task");
    $query = "%".$args['query']."%";
    $sth->bindParam("query", $query);
    $sth->execute();
    $todos = $sth->fetchAll();
    return $this->response->withJson($todos);
});


/////////////////////////////////////////////////////////////////////////////////////////////
// END POINT TO JAVASCRIPT

$app->get('/client', function (Request $request, Response $response, array $args)
{
    // Sample log message
    $this->logger->info("Slim-Skeleton '/test' route");

    // Render index view
    return $this->renderer->render($response, 'client.phtml', $args);
});


function startsWith($string, $substring)
{
    $len = strlen($substring);
    return (substr($string, 0, $len) == $substring);
}


/* test/search?term=a */
$app->get('/client/search', function($request, $response, $args)
{
   $sth = $this->db->prepare("SELECT * FROM tasks ORDER BY task");
   $sth->execute();
   $todos = $sth->fetchAll();

   $term = $request->getQueryParams()['term'];
    // $response->write('My term is: ' . $term);
    $filteredData = array();
    foreach($todos as $key => $value)
    {
        // var_dump($value['name']);
        if(startsWith(strtolower($value['task']), strtolower($term)))
        {
            // var_dump($value['name']);
            array_push($filteredData, $value);
        }
    }
    return $response->withJson($filteredData);
    // $var = startsWith('Ursina', 'Urs');
    // return $response->withJson($var);
});

/////////////////////////////////////////////////////////////////////////////////////////////

// retrieving data

$data = array(
    array('name'=> 'name 1', 'job'=>'job 1'),
    array('name'=> 'name 2', 'job'=>'job 2'),
    array('name'=> 'name 3', 'job'=>'job 3'),
);

$app->get('/test/data', function (Request $request, Response $response, array $args) use ($data)
{
    // Sample log message
    $this->logger->info("Slim-Skeleton '/test' route");

    // return data
    return $response->withJson($data);
});
