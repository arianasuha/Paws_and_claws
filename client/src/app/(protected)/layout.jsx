import Navbar from "@/components/navbar/navbar"
import { getUserIdAction } from "@/actions/authActions" // Import server action

const Layout = async ({ children }) => {
  const currentUserId = await getUserIdAction() // Fetch user ID on the server

  return (
    <>
      <Navbar currentUserId={currentUserId} /> {/* Pass the fetched user ID to Navbar */}
      <main style={{ paddingTop: "70px" }}>{children}</main>{" "}
      {/* Add padding to main content to avoid overlap with fixed navbar */}
    </>
  );
}

export default Layout
