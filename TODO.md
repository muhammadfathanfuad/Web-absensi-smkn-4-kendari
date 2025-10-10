# TODO List for Adding Checkbox Column and Bulk Actions to Manage User Tables

## 1. Fix kode_guru in TeacherController and View
- [ ] Change name="title" to name="kode_guru" in edit guru modal in manage-user.blade.php

## 2. Add Bulk Methods to Controllers
- [x] Add bulkDelete, bulkStatusActive, bulkStatusSuspended to TeacherController
- [x] Add bulkDelete, bulkStatusActive, bulkStatusSuspended to StudentController
- [x] Add bulkDelete, bulkStatusActive, bulkStatusSuspended to UserController

## 3. Add Routes for Bulk Actions
- [x] Add bulk routes for guru, murid, user in routes/web.php

## 4. Update View to Add Bulk Buttons
- [ ] Add bulk buttons in guru tab card-header
- [ ] Add bulk buttons in murid tab card-header
- [ ] Add bulk buttons in user tab card-header

## 5. Update tabel.js to Add Checkbox Column and Bulk Logic
- [ ] Add checkbox column to guru table
- [ ] Add checkbox column to murid table
- [ ] Add checkbox column to user table
- [ ] Add event listeners for bulk buttons
- [ ] Add updateBulkButtons method
- [ ] Add change event listener for checkboxes

## 6. Test the Implementation
- [ ] Test bulk delete for guru
- [ ] Test bulk status change for guru
- [ ] Test bulk delete for murid
- [ ] Test bulk status change for murid
- [ ] Test bulk delete for user
- [ ] Test bulk status change for user
