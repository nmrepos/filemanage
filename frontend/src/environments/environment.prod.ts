export const environment = {
    production: true,
    // Replace <EC2_PUBLIC_IP> with your production backend IP or domain
    apiUrl: 'http://${{ secrets.EC2_HOST }}/api'
  };
  