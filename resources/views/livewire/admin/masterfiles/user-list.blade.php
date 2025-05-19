<div class="m-5" x-data="UserListTable">
    <!-- Header with title and button group --> 
    <h2 class="text-2xl font-semibold text-gray-900 mb-5">User List  </h2>
<!-- Table -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md" x-data="employeeTable">
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
        <tr>
        
          <th class="px-6 py-4 font-medium text-gray-900">Employee Name</th>
          <th class="px-6 py-4 font-medium text-gray-900">Position</th>
          <th class="px-6 py-4 font-medium text-gray-900">Department</th>
          <th class="px-6 py-4 font-medium text-gray-900">Email</th>
          <th class="px-6 py-4 font-medium text-gray-900">Contact Number</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        <template x-for="employee in employees" :key="employee.id">
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4" x-text="employee.name"></td>
            <td class="px-6 py-4" x-text="employee.position"></td>
            <td class="px-6 py-4" x-text="employee.department"></td>
            <td class="px-6 py-4" x-text="employee.email"></td>
            <td class="px-6 py-4" x-text="employee.contact"></td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
  <script>
    document.addEventListener("alpine:init", () => {
      Alpine.data("UserListTable", () => ({
        selected: [],
        employees: [
          {
            id: 1,
            name: "Alice Santos",
            position: "Veterinary Nurse",
            department: "Animal Care",
            email: "alice.santos@example.com",
            contact: "09171234567"
          },
          {
            id: 2,
            name: "Mark Villanueva",
            position: "Receptionist",
            department: "Front Desk",
            email: "mark.v@example.com",
            contact: "09223456789"
          },
          {
            id: 3,
            name: "Dr. Karen Lim",
            position: "Veterinarian",
            department: "Surgery",
            email: "karen.lim@vetclinic.com",
            contact: "09991234567"
          }
        ],
       
      }));
    });
  </script>
  
</div>
