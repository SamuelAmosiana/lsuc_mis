LUSAKA SOUTH COLLEGE
📘 Management Information System (MIS) Development Proposal
Institution: Lusaka South College Prepared by: Samuel Sianamate Date: 08th August, 2025.
1. Overview
The proposed Management Information System (MIS) aims to digitally transform operations at Lusaka South College. The system will automate academic, administrative, and financial workflows with role-based access, providing efficiency, accountability, and transparency.
2. Development Stack
● Backend: Laravel Framework (PHP) – robust, secure, and scalable
● Frontend: Blade templating + optional Vue.js/Livewire for interactivity
● Database: MySQL
● Hosting: Cloud or VPS (Namecheap)
● Authentication: Laravel Breeze or Jetstream with role-based middleware
● Security: CSRF, XSS protection, hashed passwords, backup & audit trails
3. System Objectives
● Automate student enrollment and course registration
● Centralize academic records, financial operations and other Managerial activities.
● Provide personalized dashboards for each user role
● Improve communication between staff, students, and departments
● Support document generation (dockets, reports, invoices)
4. User Roles and Responsibilities
Staff Members
4.1 System Admin (Super Admin)
● Full system control
● Manage users, roles, and permissions
● Configure system settings
● View system-wide analytics and reports
4.2 Admin
● Basic administrative tasks
● Assist in user management
● View non-sensitive reports
4.3 Programmes Coordinator
● Create academic calendars
● Generate timetables
● Schedule programs and exams
● Publish results
● Track lecturer attendance
● Approve student course registration
4.4 Human Resource
● Manage HR operations
● Track staff attendance
● Review student records
4.5 Enrollment Officer
● Handle applications (online/physical)
● Manage accommodation assignments
● Communicate with applicants
● Schedule enrollment interviews
4.6 Accounts Officer
● Manage income and expenditures
● Maintain ledger books
● Generate finance reports
● Set and monitor student bills/fees
● View payment history and balances
4.7 Front Desk Officer
● Log meetings and visitor records
● Capture client feedback
● Handle basic HR logs
● Customer support and complaints
4.8 Librarian
● Manage library inventory (books/media)
● Log student library usage
● Track borrowed and returned items
4.9 Lecturer
● Upload CA and final exam marks
● View assigned course rosters
● Generate student performance reports
● Track student submissions
4.10 Student
● View personal profile
● Check financial balances
● View/upload results
● Register for courses
● Apply/view hostel accommodation
● Print dockets and reports
5. Dashboard Routing Based on Roles (Laravel)
Upon login, users will be redirected based on role Staff (with respective role) and Student:
public function redirectTo()
{
switch (auth()->user()->role) {
case 'super_admin':
return '/admin/dashboard';
case 'programme_coordinator':
return '/coordinator/dashboard';
case 'accounts':
return '/accounts/dashboard';
case 'lecturer':
return '/lecturer/dashboard';
case 'student':
return '/student/dashboard';
// Add other roles as needed
default:
return '/home';
}
}
6. Additional Suggestions
● Implement Audit Logs: Track who did what and when
● Notifications & Alerts: For overdue payments, result releases, etc.
● Shared File Center: Securely exchange reports, syllabi, and policies
● Role-Based Permissions Table: In the database, define and manage what actions each role can perform (CRUD rights)
7. Project Timeline (Sample)
Phase
Duration
Description
Requirements Gathering
1 week
Meetings with stakeholders
System Design
1 week
Architecture and DB planning
Backend Development
3 weeks
Laravel setup, core logic, APIs
Frontend Development
2 weeks
User interfaces and dashboards
Testing & Bug Fixing
1 week
UAT and functional testing
Deployment & Training
1 week
Host system and train staff
8. Conclusion
This MIS will serve as a central nervous system for Lusaka South College, uniting all departments with efficient tools and real-time data. Laravel's flexibility and built-in role handling make it ideal for your needs.