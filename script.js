// script.js
function initBooking() {
  const params = new URLSearchParams(window.location.search);
  const room = params.get('room');
  const roomNumber = params.get('roomNumber');
  if (room) {
    document.getElementById('room-type').value = room;
  }
  window.roomNumber = roomNumber || '';
  updateSummary();
}

function updateSummary() {
  const checkin = document.getElementById('checkin').value;
  const checkout = document.getElementById('checkout').value;
  const guests = document.getElementById('guests').value;
  const roomType = document.getElementById('room-type').value;
  const roomNumber = window.roomNumber;
  const summaryDiv = document.getElementById('summary');
  summaryDiv.innerHTML = '';
  if (!checkin || !checkout || !roomType) return;
  const dateIn = new Date(checkin);
  const dateOut = new Date(checkout);
  if (dateOut <= dateIn) {
    summaryDiv.innerHTML = 'Invalid dates: Check-out must be after check-in.';
    return;
  }
  const days = Math.ceil((dateOut - dateIn) / (1000 * 60 * 60 * 24));
  const prices = { 'Single': 100, 'Double': 150, 'Suite': 250 };
  const price = prices[roomType];
  const total = days * price;
  fetch(`check_availability.php?room_type=${encodeURIComponent(roomType)}&room_number=${encodeURIComponent(roomNumber)}&checkin=${checkin}&checkout=${checkout}`)
    .then(res => res.json())
    .then(data => {
      if (!data.available) {
        summaryDiv.innerHTML = 'Selected room is not available for these dates.';
        return;
      }
      summaryDiv.innerHTML = `
  Room Type: ${roomType}<br>
  ${roomNumber ? `Room Number: ${roomNumber}<br>` : ''} // Likely line 41
  Check-in: ${checkin}<br>
  Check-out: ${checkout}<br>
  Guests: ${guests}<br>
  Nights: ${days}<br>
  Total Cost: $${total}
`;
    })
    .catch(err => {
      summaryDiv.innerHTML = 'Error checking availability.';
    });
}

async function submitBooking(event) {
  event.preventDefault();
  const checkin = document.getElementById('checkin').value;
  const checkout = document.getElementById('checkout').value;
  const guests = document.getElementById('guests').value;
  const roomType = document.getElementById('room-type').value;
  const roomNumber = window.roomNumber;
  const data = { checkin, checkout, guests, roomType, roomNumber };
  const response = await fetch('submit_booking.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  const result = await response.json();
  if (result.success) {
    window.redirectTo = result.role ? `${result.role}_dashboard.php` : 'index.php';
    const modal = document.getElementById('confirmation-modal');
    const modalDetails = document.getElementById('modal-details');
    const details = result.details;
    modalDetails.innerHTML = `
      Your reservation has been successfully made!<br>
      Room Type: ${details.roomType}<br>
      Room Number: ${details.roomNumber}<br>
      Check-in: ${details.checkin}<br>
      Check-out: ${details.checkout}<br>
      Guests: ${details.guests}<br>
      Total Cost: $${details.total}
    `;
    modal.style.display = 'flex';
  } else {
    alert(result.error);
  }
}

function closeModal() {
  document.getElementById('confirmation-modal').style.display = 'none';
  window.location.href = window.redirectTo;
}