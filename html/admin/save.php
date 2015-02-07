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

        case 'newPage':
            // @todo: filter/sanitize
            // @todo: do not allow spaces in key. replace with underscore or -

            if(isset($_GET['newPageKey']) && mb_strlen(trim($_GET['newPageKey'])) > 0) {
                if(stripos($_GET['newPageKey'], 'page:') !== false) {
                    echo json_encode(array('status' => 'failed', 'message' => 'Adresse/URL er ugyldig', 'element' => 'newPageKey'), true);
                } else {
                    $newPageKey = 'page:'.trim($_GET['newPageKey']);
                    $predisClient = $login->getPredisClient();
                    if($predisClient->sismember($login->getMasterKey().':allpages', $newPageKey)) {
                        // Page/URL already exists.
                        echo json_encode(array('status' => 'failed', 'message' => 'Adresse/URL er allerede i bruk', 'element' => 'newPageKey'), true);
                    } else {
                        // Check data, then add to page set, then to page hash.
                        $title = trim(htmlspecialchars($_GET['title']));
                        if(mb_strlen($title) == 0) {
                            echo json_encode(array('status' => 'failed', 'message' => 'Tittel mangler', 'element' => 'title'), true);
                        } else {
                            $predisClient->sadd($page->getMasterKey().':allpages', array($newPageKey));

                            $page = new \Enkeltinnhold\Page($newPageKey);

                            $page->title = trim(htmlspecialchars($_GET['title']));
                            $page->digest = trim(htmlspecialchars($_GET['digest']));
                            $page->created = date('c');
                            $page->updated = date('c');
                            $page->updatedBy = $login->getLoggedInUser();

                            $pageData = trim(htmlspecialchars($_GET['pageData']));
                            $page->setPageData($pageData);

                            if($page->save()) {
                                // Yay
                                echo json_encode(array('status' => 'saved', 'message' => 'Ny side lagret. Sender deg tilbake til kontrollpanelet om 5 sekunder.'), true);
                            } else {
                                // Fail
                                echo json_encode(array('status' => 'failed', 'message' => 'Klarte ikke lagre side'), true);
                            }
                        }
                    }
                }


            } else {
                echo json_encode(array('status' => 'failed', 'message' => 'Adresse/URL mangler', 'element' => 'newPageKey'), true);
            }




            break;
    }
}
require_once('../../include/shutdown.php');