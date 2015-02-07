<?php
require_once('../../include/startup.php');
$login = new Enkeltinnhold\Login();

header('Content-Type: application/json');
if($login->isLoggedIn() == false) {
    echo json_encode(array('status' => 'not-logged-in'), true);
}

if(isset($_GET['action'])) {
    switch($_GET['action']) {
        case 'pageSave':

            // @todo: filter/sanitize

            $pageKey = $_GET['pageKey'];

            $page = new \Enkeltinnhold\Page($pageKey);
            if($page->load()) {

                $page->title = trim(htmlspecialchars($_GET['title']));
                $page->digest = trim(htmlspecialchars($_GET['digest']));
                $page->updated = date('c');
                $page->updatedBy = $login->getLoggedInUser();

                if($page->created == null) {
                    $page->created = $page->updated;
                }

                $pageData = trim(htmlspecialchars($_GET['pageData']));
                $page->setPageData($pageData);

                if($page->save()) {
                    // Yay
                    echo json_encode(array('status' => 'saved'), true);
                } else {
                    // Fail
                    echo json_encode(array('status' => 'failed'), true);
                }
            } else {
                // Fail, invalid page
                echo json_encode(array('status' => 'failed'), true);
            }
            break;
    }
}
require_once('../../include/shutdown.php');