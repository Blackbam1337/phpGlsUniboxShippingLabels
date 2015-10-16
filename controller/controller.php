<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 10/9/15
 * Time: 8:31 PM
 */


$klein = new \Klein\Klein();

$klein->respond('GET', '/', function ($request, $response, $service, $app) {

    $service->render("view/main.php");
});


$klein->respond('POST', '/create/', function ($request, $response, $service, $app) {

    $c = new Creator();
    $c->createFromJson($_POST["data"]);

    global $errors;
    $errors = array_merge_recursive($errors,$c->getErrors());

    if(empty($errors)) {
        $c->flush();
    } else {
        return json_encode($errors);
    }
});


$klein->respond('POST', '/create_from_interface/', function ($request, $response, $service, $app) {

    // 1. Create the JSON
    $prepare = array();

    $prepare["tags_format"] = $_POST["tags_format"];

    $prepare["tags"] = $_POST["gls_input"];

    $prepare["package"] = array();

    $prepare["package"]["label"] = $_POST["package_label"];
    $prepare["package"]["number"] = $_POST["package_number"];

    $prepare["pdf"] = array();

    $prepare["pdf"]["beginx"] = intval($_POST["pdf_beginx"]);
    $prepare["pdf"]["beginy"] = intval($_POST["pdf_beginy"]);
    $prepare["pdf"]["format"] = $_POST["pdf_papersize"];
    $prepare["pdf"]["prefix"] = $_POST["pdf_filename_prefix"];

    $prepare["mode"] = $_POST["mode"];

    $encoded = json_encode($prepare);


    // 2. Either return the JSON or Generate the PDF
    if(isset($_POST["create_json_only"]) && $_POST["create_json_only"]=="1") {

        global $output_json;
        $output_json = $prepare;
        $service->render("view/main.php");
        die;

    } else {

        $c = new Creator();
        if($c->createFromJson($encoded)) {
            if(empty($c->getErrors())) {
                $c->flush();
                die;
            }
        }
        global $errors;
        $errors = array_merge_recursive($errors,$c->getErrors());
        $service->render("view/main.php");
        die;
    }
});


$klein->respond('POST', '/[*]', function ($request, $response, $service, $app) {
    return json_encode(new Error("Error: Invalid call."));
});


$klein->dispatch();