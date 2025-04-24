
Student Database Management System 

Database Design
2.1	Entity-Relationship Diagram (ERD)
Conceptual ERD
![image](https://github.com/user-attachments/assets/efa8c398-e69e-4c39-9699-02bef9a6fdfd)
  In this conceptual ERD, there are 6 tables Student, Section, Subject, Schedule Assessment, and Result for the junction tables I have made 3 which are named StudentSchedule,  StudentSubject, ScheduleSuject. The student table has a many-to-many relationship with the schedule and subject thus the 2 junction tables were needed as the schedule and subject have a many-to-many relationship with each other therefore the junction table schedulesubject were also created. The subject table has a relationship with the assessment table for each assessment type is unique to what subject is created by the student and the student table is directly linked to the subject making the subject that the student has unique to many subject, while the student takes the result of the assessment through the results table, thus the student has a relationship to the results table.

Physical ERD
![image](https://github.com/user-attachments/assets/2898da01-1cc8-4ae1-8543-498b4893bf8a)
  The physical ERD shows the field’s datatype primary keys/foreign keys and their relationships. 
2.2	Database Schema
section table
| KEY | FIELD NAME    | DATATYPE    |
| :-: | :-----------: | :---------: |
| PK  | section_id    | int(11)     |
|     | section_name  | varchar(255)|

student table
| KEY | FIELD NAME         | DATATYPE     |
| :-: | :----------------: | :----------: |
| PK  | student_id         | int(11)      |
|     | first_name         | varchar(255) |
|     | middle_name        | varchar(255) |
| AK  | student_number     | varchar(255) |
| FK  | section_id         | int(11)      | 
| AK  | email              | varchar(255) |
|     | password           | varchar(255) |

schedule table
| KEY | FIELD NAME         | DATATYPE     |
| :-: | :----------------: | :----------: |
| PK  | schedule_id        | int(11)      |
|     | day_of_week        | enum         |
|     | time_in            | varchar(255) |
|     | time_out           | varchar(255) |
|     | is_deleted         | tinyint      | 

subject table
| KEY | FIELD NAME         | DATATYPE     |
| :-: | :----------------: | :----------: |
| PK  | subject_id         | int(11)      |
| FK  | student_id         | int(11)      |
|     | subject_name       | varchar(255) |
|     | instructor         | varchar(255) |
|     | is_deleted         | tinyint      | 

studentschedule table
| KEY | FIELD NAME         | DATATYPE       |
| :----:| :----------------: | :----------: |
| PK,FK | student_id         | int(11)      |
| PK,FK | schedule_id        | int(11)      |

studentsubject table
| KEY | FIELD NAME         | DATATYPE       |
| :----:| :----------------: | :----------: |
| PK,FK | student_id         | int(11)      |
| PK,FK | subject_id         | int(11)      |

schedulesubject table
| KEY | FIELD NAME         | DATATYPE       |
| :----:| :----------------: | :----------: |
| PK,FK | schedule_id        | int(11)      |
| PK,FK | subject_id         | int(11)      |

assessment table
| KEY | FIELD NAME         | DATATYPE     |
| :-: | :----------------: | :----------: |
| PK  | assessment_id      | int(11)      |
| FK  | assessment_name    | varchar(255) |
| FK  | subject_id         | int(11)      |
| FK  | student_id         | int(11)      |
|     | type               | enum         |
|     | is_deleted         | tinyint      | 

results table
| KEY | FIELD NAME         | DATATYPE     |
| :-: | :----------------: | :----------: |
| PK  | results_id         | int(11)      |
| FK  | assessment_id      | int(11)      |
| FK  | student_id         | int(11)      |
|     | score              | enum         |

Features and Functionalities
3.1 Student Information Management
  This is the landing page of the project; it consists of 5 options(excluding the home button) that will take you to each page of the project.
Student.php
![image](https://github.com/user-attachments/assets/fbeb24e6-7edd-4071-98fe-274814e84ca7)
  One of the options is the Get Started button. This page displays the student information, but they must create an account first or log in if already registered. Below is the create/register page where you will insert your personal information to create an account.
Registerpage.php
![image](https://github.com/user-attachments/assets/d49ace16-391a-4b6a-acc0-b8b4b1a02940)
  After creating/registering an account you can log in by inserting your email and password and by clicking the login button. 
Login.php
![image](https://github.com/user-attachments/assets/237b4589-4fdc-4f1c-bcbc-85eed49752a6)
  The user can also click the forgot password button if somehow accidentally forgot their password, they will be redirected to the forgot password page, and they can reset their password information.
forgotpass.php
![image](https://github.com/user-attachments/assets/279716cc-45c2-45f0-9b97-eba1ba26ef4b)
  After logging in, you will be redirected to the student page where all the student's personal information is displayed.
Studentinfo.php
![image](https://github.com/user-attachments/assets/2f727454-3d53-47b8-a730-f09053a80329)
  By clicking the edit button, you will be redirected to the edit information  page or update student information page where you can edit/modify your personal information.
Update_studentinfo.php
  ![image](https://github.com/user-attachments/assets/baac2a18-05c3-4c2b-8840-57174815b0ed)
3.2 Subject and Schedule Management
	After checking the user's information page, the user can also click the syllabus page where the user can see the list of his/her subject and schedule.
Syllabus.php
![image](https://github.com/user-attachments/assets/372d68a7-f2de-418f-b73c-b088bf0d72c7)
 	Users can also add another subject and schedule by clicking the add syllabus button. This way they will be redirected to the add syllabus page.
Syllabus.php
![image](https://github.com/user-attachments/assets/76394b24-f77d-4015-8ac6-3c91af11a42f)
  The user can also update their information by clicking the update button from the syllabus page. The UI design was the same as the add syllabus page, but their functionality is different this time as it updates the already created subject and schedule information.
Update_syllabus.php
![image](https://github.com/user-attachments/assets/7ef236a1-15be-427f-8af0-d850f334b96b)
  The user can also delete the subject and schedule data/information by clicking the delete button from the syllabus page, the system will notify the user if he/she wants to delete data/information, this serves as a warning from a data deletion. One of the small functionalities that are added is the backup system where the subject and schedule data/information are stored in a CSV file format saved in backup folder, this way the user can view deleted contents for future reference.
Delete_syllabus.php
![image](https://github.com/user-attachments/assets/3f312ea3-e690-4930-9656-926ae5932373)
3.3 Quiz, Activity, and Exam Tracking
  After the syllabus page, there is also an assessment page where the user can add, track/read, update, and delete their assessment information.
Assessment.php
![image](https://github.com/user-attachments/assets/a765c8ba-471d-43b4-97b8-1aae71c0236e)
  This is the update assessment page. Same UI and functionality as the syllabus page.
Update_assessment.php
![image](https://github.com/user-attachments/assets/59ddc7cd-b68d-42af-a535-dad07538f1eb)
Delete_assessment.php
![image](https://github.com/user-attachments/assets/15edf3f6-337c-4402-a4a2-eb494e585ff4)
	The system also has a search functionality for both syllabus and assessment pages. This way the user can easily identify what schedule or assessment they want to see or what information they want to update or delete.
![image](https://github.com/user-attachments/assets/43ff81ac-ca3d-482a-802a-d91d3a4b3e13)
  After all of that, the user can log out from the system, and they will be redirected to the login page where they can log in and come back anytime.
![image](https://github.com/user-attachments/assets/358b6362-abff-48ee-bb47-57988753133d)
  And lastly, one unnecessary page that still holds importance is the About page. This concludes the purpose of the system. Because we believe students should not be left behind by poor systems. This tool is a step toward giving them back time and control—so they can focus on what matters: learning.

