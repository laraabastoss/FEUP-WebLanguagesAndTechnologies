PRAGMA FOREIGN_KEYS = ON;

DROP TABLE IF EXISTS Comments;
DROP TABLE IF EXISTS Frequently_Asked_Questions;
DROP TABLE IF EXISTS Suggested_Frequently_Asked_Questions;
DROP TABLE IF EXISTS Updates;
DROP TABLE IF EXISTS Tickets;
DROP TABLE IF EXISTS Department_Agent;
DROP TABLE IF EXISTS Departments;
DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
    user_id INTEGER NOT NULL PRIMARY KEY,
    username TEXT,
    email TEXT,
    bio TEXT,
    password VARCHAR(255),
    role TEXT CHECK (role IN ('admin', 'customer'))
);

CREATE TABLE Tickets (
    ticket_id INTEGER NOT NULL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    agent_id INTEGER,
    department_id INTEGER NOT NULL,
    title TEXT,
    description TEXT,
    status TEXT CHECK (status IN ('open', 'in progress', 'resolved', 'closed')),
    priority TEXT CHECK (priority IN ('low', 'medium', 'high')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    hashtags JSON DEFAULT '[]',
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (department_id, agent_id) REFERENCES Department_Agent(department_id, agent_id)
);

CREATE TABLE Departments (
    department_id INTEGER NOT NULL PRIMARY KEY,
    name TEXT
);

CREATE TABLE Comments (
    comment_id INTEGER NOT NULL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    ticket_id INTEGER NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES Tickets(ticket_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Department_Agent(
    department_id INTEGER NOT NULL,
    agent_id INTEGER,
    PRIMARY KEY (department_id, agent_id),
    FOREIGN KEY (department_id) REFERENCES Departments(department_id),
    FOREIGN KEY (agent_id) REFERENCES Users(user_id)
);

CREATE TABLE Frequently_Asked_Questions (
    question_id INTEGER NOT NULL,
    title TEXT,
    description TEXT,
    PRIMARY KEY (question_id)
);

CREATE TABLE Suggested_Frequently_Asked_Questions (
    question_id INTEGER NOT NULL,
    title TEXT,
    PRIMARY KEY (question_id)
);



CREATE TABLE Updates(
    update_id INTEGER NOT NULL,
    ticket_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_before TEXT NOT NULL,
    status_after TEXT NOT NULL,
    department_before INTEGER NOT NULL,
    department_after INTEGER NOT NULL,
    agent_before INTEGER ,
    agent_after INTEGER , 
    priority_before TEXT NOT NULL,
    priority_after TEXT NOT NULL,
    added_hashtag TEXT,
    removed_hashtag TEXT,
    PRIMARY KEY (update_id),
    FOREIGN KEY (ticket_id) REFERENCES Tickets(ticket_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (agent_before) REFERENCES Users(user_id),
    FOREIGN KEY (agent_after) REFERENCES Users(user_id),
    FOREIGN KEY (department_before) REFERENCES Departments(department_id ),
    FOREIGN KEY (department_after) REFERENCES Departments(department_id)
);

/*All passwords are Teste123*/
INSERT INTO Users VALUES (1, "johnyBravo", "defaultuser@gmail.com", "Tell us about yourself", "$2y$10$TmIrzGfdOY3q/Twg3Hb0nOfS7Yo2ijJbCALQdwY4KoXkE2/Xbgwly", "customer");
INSERT INTO Users VALUES (2, "laraabastoss", "defaultagent1@gmail.com", "Tell us about yourself", "$2y$10$TmIrzGfdOY3q/Twg3Hb0nOfS7Yo2ijJbCALQdwY4KoXkE2/Xbgwly", "customer");
INSERT INTO Users VALUES (3, "ritagp", "defaultagent2@gmail.com", "Tell us about yourself", "$2y$10$TmIrzGfdOY3q/Twg3Hb0nOfS7Yo2ijJbCALQdwY4KoXkE2/Xbgwly", "customer");
INSERT INTO Users VALUES (4, "carlosmcostaGH", "defaultagent3@gmail.com", "Tell us about yourself", "$2y$10$TmIrzGfdOY3q/Twg3Hb0nOfS7Yo2ijJbCALQdwY4KoXkE2/Xbgwly", "customer");
INSERT INTO Users VALUES (5, "aerestivo", "defaultadmin@gmail.com", "Administrator", "$2y$10$TmIrzGfdOY3q/Twg3Hb0nOfS7Yo2ijJbCALQdwY4KoXkE2/Xbgwly", "admin");

INSERT INTO Departments(name) VALUES("Project Managing");
INSERT INTO Departments(name) VALUES("Backend");
INSERT INTO Departments(name) VALUES("Frontend");
INSERT INTO Departments(name) VALUES("UI/UX Design");
INSERT INTO Departments(name) VALUES("Data Science");
INSERT INTO Departments(name) VALUES("Artificial Intelligence");
INSERT INTO Departments(name) VALUES("Web Programming");
INSERT INTO Departments(name) VALUES("Compilers");
INSERT INTO Departments(name) VALUES("Other");

INSERT INTO Department_Agent VALUES(1, NULL); /*Project managing*/
INSERT INTO Department_Agent VALUES(2, NULL); /*Backend*/
INSERT INTO Department_Agent VALUES(3, NULL); /*FrontEnd*/
INSERT INTO Department_Agent VALUES(4, NULL); /* UI/UX Design */
INSERT INTO Department_Agent VALUES(5, NULL); /*Data Science*/
INSERT INTO Department_Agent VALUES(6, NULL); /*Artificial Intelligence*/
INSERT INTO Department_Agent VALUES(7, NULL); /*Web Programming*/
INSERT INTO Department_Agent VALUES(8, NULL); /* Compilers */
INSERT INTO Department_Agent VALUES(9, NULL); /* Other */

INSERT INTO Department_Agent VALUES(1, 2); /*Project managing Agent 1*/
INSERT INTO Department_Agent VALUES(2, 2); /*Backend Agent 1*/
INSERT INTO Department_Agent VALUES(3, 3); /*FrontEnd Agent 2*/
INSERT INTO Department_Agent VALUES(4, 4); /* UI/UX Design Agent 3*/

INSERT INTO Tickets VALUES(1,1,NULL,1, "How can we enhance the User Authentication Module?", "How can we strengthen the password security measures? Are there any additional authentication methods we should consider implementing?", "open", "low", "09-02-2022", "09-02-2022", '["authentication"]');
INSERT INTO Tickets VALUES(2,1,2,2, "How can we optimize database queries for improved performance?", "Looking for suggestions on optimizing database queries to reduce response times and enhance overall backend performance. Any recommended techniques or best practices?", "in progress", "medium", "10-03-2022", "10-03-2022", '["database","queries"]');
INSERT INTO Tickets VALUES(3,1,NULL,2, "What are the best practices for handling API rate limiting?", "Seeking advice on implementing effective API rate limiting strategies to prevent abuse and ensure fair usage. Any recommendations on rate limit algorithms or tools?", "open", "low", "01-07-2022", "01-07-2022", '["algorithms"]');
INSERT INTO Tickets VALUES(4,1,3,3, "How can we improve mobile responsiveness of our web application?", "Looking for suggestions and techniques to enhance the mobile responsiveness of our web application. Any tips on optimizing layout, media queries, or frameworks?", "in progress", "high", "01-09-2022", "01-09-2022", '["responsiviness","layout"]');
INSERT INTO Tickets VALUES(5,1,NULL,3, "What are some effective ways to optimize JavaScript performance?", "Seeking advice on improving JavaScript performance for a smoother and faster user experience. Any recommended techniques, tools, or coding practices?", "open", "low", "01-02-2023", "01-02-2023", '["tag"]');
INSERT INTO Tickets VALUES(6,1,4,4, "How can we enhance the accessibility of our user interface?", "Looking for insights and recommendations on improving the accessibility of our UI design. Any guidelines, tools, or best practices for ensuring inclusivity?", "closed", "low", "06-02-2023", "06-02-2023", '["design"]');
INSERT INTO Tickets VALUES(7,1,NULL,4, "What are some effective methods for gathering user feedback on UI/UX?", "Seeking suggestions on gathering valuable user feedback to enhance our UI/UX design. Any recommended feedback collection methods or usability testing approaches?", "open", "low", "01-04-2023", "01-04-2023", '["testing"]');

INSERT INTO Frequently_Asked_Questions VALUES(1,"How can I create a ticket?", "To create a ticket, click on the New Ticket button, then you can choose a title, a description, a department, attach file and add some hashtags.");
INSERT INTO Frequently_Asked_Questions VALUES(2,"How can I become an agent?", "To become an agent, you need to be assigned to a department. The administrators are responsible for assigning users to departments and turning them into agents.");
INSERT INTO Frequently_Asked_Questions VALUES(3,"How can I comment on a ticket?", "When you open a ticket, if you have permission to comment, that is if you are the user that created the ticket or the agent assigned to it, you will see a section at the end of the page, that allows you to add comments to the ticket.");

INSERT INTO Comments VALUES(1,2,2,"Optimizing database queries for improved performance involves techniques such as efficient indexing, query optimization, caching, denormalization, partitioning, hardware optimization, and regular maintenance. Hope this helps!","02-03-2023");
INSERT INTO Comments VALUES(2,1,2,"What are some common challenges in optimizing database queries for improved performance?","03-03-2023");
INSERT INTO Comments VALUES(3,2,2,"Common challenges in optimizing database queries for improved performance include inadequate indexing, complex query logic, limited query optimization knowledge, and issues related to data volume and distribution.","05-03-2023");
INSERT INTO Comments VALUES(4,3,4,"To improve mobile responsiveness, follow these steps:
1. Use responsive design: Implement techniques like fluid grids and media queries to ensure your web application adapts well to different screen sizes.
2. Optimize images and media: Compress and resize images appropriately for mobile devices to reduce loading times.
3. Minimize page load times: Optimize code, minimize HTTP requests, and leverage caching techniques to ensure fast loading on mobile devices.
4. Simplify navigation: Streamline menus and navigation elements for better usability on smaller screens.
5. Test on various devices: Regularly test your web application on different mobile devices to identify and resolve any responsiveness issues.
6. Prioritize content: Display the most important content prominently and avoid clutter on mobile screens.
Remember, creating a seamless mobile experience requires ongoing testing, optimization, and user feedback.","01-10-2022");
INSERT INTO Comments VALUES(5,1,6,"What are some important considerations for implementing proper color contrast in UI design to enhance accessibility for users with visual impairments?","10-02-2022");

INSERT INTO Updates VALUES(1,2,2,"26-03-2023", "open", "in progress", 2,2, NULL, 2, "low", "low", NULL, NULL);
INSERT INTO Updates VALUES(2,2,2,"27-03-2023", "in progress", "in progress", 2,2, 2, 2, "low", "medium", NULL, NULL);

INSERT INTO Updates VALUES(3,4,3,"28-03-2023", "open", "in progress", 3,3, NULL, 3, "low", "low", NULL, NULL);
INSERT INTO Updates VALUES(4,4,3,"29-03-2023", "in progress", "in progress", 3,3, 3, 3, "low", "high", NULL, NULL);

INSERT INTO Updates VALUES(5,6,4,"10-04-2023", "open", "in progress", 4,4, NULL, 4, "low", "low", NULL, NULL);
INSERT INTO Updates VALUES(6,6,4,"12-04-2023", "in progress", "in progress", 4,4, 4, 4, "low", "medium", NULL, NULL);

