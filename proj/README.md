# Ticket Management Project  
**LTW- project-ltw06g06**

## Description

This website manages trouble tickets related to different technology topics, enabling users to submit, track, and resolve tickets. Furthermore, the tickets are divided into departments and each department has a group of specilized agents that can help solve the tickets.

## Roles

There are different types of roles an user of the website can have:

* **Clients**: Can submit new tickets and comment on their tickets, as well as, change their status and edit hashtags. Besides that, they can see all the tickets in the website and their comments. Plus, they can ask questions about the website that can eventually be updated to FAQs by the administrators.

* **Agents**: Agents are also clients, so they can do everything a client can, but, in addition to that, they are assigned to a department and, therefore,  may assign themselves (and others) to tickets in that department. When they are assigned to a ticket they can comment on it, change its status and edit the hashtags.


* **Administrators**: Administrators have full control over the website. They can do everything a client can, and they can also be agents. However, administrators are able to assign new agents to departments, upgrade Suggested FAQs to FAqs, upgrade clients to administrators and see agents stats.

### Example of user accounts:
 defalutuser@gmail.com

 defaultagent1@gmail.com

 defaultagent2@gmail.com

 defaultagent3@gmail.com

 defaultadmin@gmail.com

 **Password**: Teste123


## Features
- Create a ticket
- Search tickets by title or hashtags
- Comment on tickets
- Change ticket status
- Change ticket department
- Edit hashtags
- See ticket history
- See personal tickets
- Edit profile
- Filter tickets (agents)
- Assign tickets (agents)
- Upgrade client to administrator (admins)
- Assign agent to department (admins)


Besides the requested features we also decided to add some extra features

- Add a file to a ticket
- Near the search of tickets to a specific department
- Suggest FAQs
- Agents can be assigned do multiple departments
- See agent stats (admins)

## Screenshots

<img width="1406" alt="Screenshot 2023-05-21 at 21 56 48" src="https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/861797de-543b-4978-ae97-570eaa7e2c99">

<img width="1421" alt="Screenshot 2023-05-21 at 21 57 41" src="https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/45a5b531-2283-48d8-a323-b51044c913be">

<img width="1426" alt="Screenshot 2023-05-21 at 21 59 50" src="https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/ffe28abb-5d73-4f49-9fba-e09db4969f43">

<img width="1415" alt="Screenshot 2023-05-21 at 22 01 08" src="https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/213be5c6-f32f-4ee8-9a18-99f5977b5039">

<img width="1426" alt="Screenshot 2023-05-21 at 22 01 41" src="https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/7d5a91c3-e29f-4146-aa56-7b3d01ac188a">

<img width="1409" alt="Screenshot 2023-05-21 at 22 04 49" src="https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/d438a803-1042-439f-895a-60215f67b203">

<img width="1415" alt="Screenshot 2023-05-21 at 22 07 04" src="https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/96ed60b5-9d11-4613-bed2-41310fab382a">

![WhatsApp Image 2023-05-21 at 10 11 31 PM](https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/54e12005-17e8-4f5b-8333-45319fbe0276)

<img width="1422" alt="Screenshot 2023-05-21 at 22 16 06" src="https://github.com/FEUP-LTW-2023/project-ltw06g06/assets/92671491/22a179a1-c2dd-45ee-9632-15c3760184c0">


## Execute project
        git clone git@github.com:FEUP-LTW-2023/project-ltw06g06.git
        git checkout final-delivery-v1
        cd database
        sqlite3 database/database.db < database/database.sql
        php -S localhost::9000

## Developed by
    Carlos Manuel da Silva Costa  up202004151
    Lara Santos Bastos            up202108740
    Rita Gonçalves Pereira        up202108746
