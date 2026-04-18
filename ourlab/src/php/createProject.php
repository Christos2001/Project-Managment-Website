<?php 
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/secure_cookies.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/check_auth.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php/connect_to_DB.php';
        

        

    

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_SESSION['user'];
            $title = $_POST['title'];
            try{ 
                $prp1 = $conn->prepare("INSERT INTO projectlist (title, creator, Date) VALUES (?, ?, CURRENT_TIMESTAMP())");
                $prp1->bind_param("ss", $title, $username);
                $conn->begin_transaction();
                if ($prp1->execute()) {
                        $prp1->close();
                } else {
                    $prp1->close();
                    throw new Exception(1);                    
                }
                $newListId = $conn->insert_id; 

                $prp2 = $conn->prepare("INSERT INTO list_permissions (list_id, username) VALUES (?, ?)");

                $prp2->bind_param("is", $newListId, $username);

                if ($prp2->execute()) {
                    $prp2->close();
                    $conn->commit();
                    echo '<script type="text/javascript">';
                    echo 'alert("Project has been created!");';
                    echo  "window.location.href ='../html/user/projects/myProjects.html'";
                    echo '</script>';
                } else {
                    $prp2->close();
                    throw new Exception(2);                    
                }
            }catch(Exception $e){
                $conn->rollback();
                echo '<script type="text/javascript">';
                echo 'alert("An error has occured.");';
                echo  "window.location.href ='../html/user/projects/createList.html'";
                echo '</script>';
            }
        }
    
    ?>

