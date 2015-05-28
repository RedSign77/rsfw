<?php

if (!isset($_SESSION['core']) || $_SESSION['core'] != 1)
    die("Core not loaded!");
$_SESSION['BASE'] = "http://";
$_SESSION['REQUEST'] = isset($_GET['req']) ? $_GET['req'] : null;
/**
 *  Create default objects
 */
if (!isset($user) || !is_object($user)) {
    $user = new User();
}
if (!isset($db) || !is_object($db))
    $db = IDatabase::getSingleton();

if (!isset($pager) || !is_object($pager))
    $pager = new Pager();


// READ CONFIG
$cfg = $db->getOneRow(TBL_CONFIG, "id=".VERSION);

if(User::isLogged())
{
    include 'includes/post.check.php';
}

include_once 'includes/page-datas.php';

/**
 * Check request and set page
 */
$_SESSION['page'] = $arrPages['kezdolap'];

if (User::isLogged() && $_SESSION['page']['id'] == "login")
    $_SESSION['page'] = $arrPages['kezdolap'];

if (isset($_SESSION['REQUEST'])) {
    $_SESSION['GURL'] = explode("/", $_SESSION['REQUEST']);
    if (isset($arrPages[$_SESSION['GURL'][0]])) {
        $key = $_SESSION['GURL'][0];
        $page = $arrPages[$key];
        if ($page['rank'] > 0 && !User::isLogged()) {
            //$_SESSION['page'] = $arrPages['login'];
            $_SESSION['page'] = $arrPages['kezdolap'];
        } else {
            if ($key == "login" && User::isLogged())
                $_SESSION['page'] = $arrPages['kezdolap'];
            else
                $_SESSION['page'] = $page;
        }
    }
}
if(User::isLogged() && $_SESSION['page']['id']=="logout")
    $user->logout();
/**
 * Set processes
 */

if (is_file("process/p_" . $_SESSION['page']['file']))
    include 'process/p_' . $_SESSION['page']['file'];
elseif (isset($_GET['debug']))
    echo "<br>Warning: No default process found.";
if (isset($_SESSION['page']['process'])) {
    if (is_array($_SESSION['page']['process'])) {
        foreach ($_SESSION['page']['process'] as $value) {
            if (is_file("process/" . $value))
                include_once "process/" . $value;
            elseif (isset($_GET['debug']))
                echo "<br>Warning: " . $value . " process not found.";
        }
    }
}
/*
include_once 'pages/header.php';
include_once 'pages/' . $_SESSION['page']['file'];
include_once 'pages/footer.php';
*/