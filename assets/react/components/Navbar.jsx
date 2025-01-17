import { Card } from "primereact/card";
import { Button } from "primereact/button";

export function Navbar() {
    return (
        <Card
            className="p-card-navbar"
            style={{
                padding: "0rem",
            }}
        >
            <a style={{ textDecoration: "none", color: "#4b5563" }} href="/">
                <h4
                    style={{
                        margin: 0,
                        padding: 0
                    }}
                >
                    E-Commerce
                </h4>
            </a>
            <section
                style={{
                    display: "flex",
                    alignItems: "center",
                    gap: ".5rem"
                }}
            >
                <Button>Log In</Button>
                <Button severity="secondary">Register</Button>
            </section>
        </Card>
    )
}