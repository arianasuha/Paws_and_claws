import "@/styles/globals.css";

export const metadata = {
  title: "Paws and Claws",
  description: "Pet Adoption Platform",
};

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
