import { PrimeReactProvider } from 'primereact/api';
import { Navbar } from "./Navbar";

export function Layout({ children, user }) {
    const value = {
        ripple: true
    }

    return (
        <PrimeReactProvider value={value}>
            <div style={{ padding: ".5rem", height: "100%" }}>
                <Navbar user={user} />
                { children }
            </div>
        </PrimeReactProvider>
    )
}