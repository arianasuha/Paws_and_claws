import Navbar from "@/components/navbar/navbar"

const Layout = async ({ children }) => {
  return (
    <>
      <Navbar /> 
      <main style={{ paddingTop: "70px" }}>{children}</main>{" "}
    </>
  );
}

export default Layout
