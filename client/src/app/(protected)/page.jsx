import Image from "next/image"
import { HomePageButton } from "@/components/buttons/buttons"
import ServiceCards from "@/components/cards/service-cards"
import styles from "./page.module.css"

export default function ProtectedPage() {
  return (
    <div className={styles.container}>
      <section className={styles.heroSection}>
        <div className={styles.heroBackground}>
          <Image 
            src="/landing-image.jpeg" 
            alt="Pet care landing banner" 
            fill 
            className={styles.heroImage} 
          priority />
          <div className={styles.heroOverlay} />
        </div>
        <div className={styles.heroContent}>
          <h1 className={styles.heroTitle}>Welcome to Paws and Claws</h1>
          <p className={styles.heroSubtitle}>Your trusted partner in comprehensive pet care and wellness services</p>
          <HomePageButton title={"Shop Pets"} link={"/shop"} />
        </div>
      </section>

      <section id="services" className={styles.servicesSection}>
        <ServiceCards />
      </section>

      <section className={styles.bookingSection}>
        <div className={styles.bookingContainer}>
          <div className={styles.bookingContent}>
            <h2 className={styles.bookingTitle}>Book Your Session Today</h2>
            <p className={styles.bookingDescription}>
              Schedule an appointment with our experienced veterinarians and give your pet the care they deserve. We
              provide comprehensive health checkups, vaccinations, and emergency care services with compassionate,
              professional attention.
            </p>
            <HomePageButton title={"Book Session"} link={"/appointments"} />
          </div>
          <div className={styles.bookingImageContainer}>
            <Image
              src="/dog-cat.jpg"
              alt="Happy dog and cat together"
              width={600}
              height={600}
              className={styles.bookingImage}
            />
          </div>
        </div>
      </section>

      <footer className={styles.footer}>
        <div className={styles.footerContainer}>
          <div className={styles.footerGrid}>
            <div className={styles.footerColumn}>
              <h3 className={styles.footerBrand}>Paws and Claws</h3>
              <p className={styles.footerDescription}>
                Your trusted partner in pet health and wellness. Providing comprehensive care for all your furry friends
                with love and expertise.
              </p>
            </div>
            <div className={styles.footerColumn}>
              <h4 className={styles.footerHeading}>Our Services</h4>
              <ul className={styles.footerList}>
                <li>
                  <a href="#veterinary" className={styles.footerLink}>
                    Veterinary Care
                  </a>
                </li>
                <li>
                  <a href="#grooming" className={styles.footerLink}>
                    Pet Grooming
                  </a>
                </li>
                <li>
                  <a href="#shelter" className={styles.footerLink}>
                    Pet Shelter
                  </a>
                </li>
                <li>
                  <a href="#lost-pets" className={styles.footerLink}>
                    Lost Pet Recovery
                  </a>
                </li>
              </ul>
            </div>
            <div className={styles.footerColumn}>
              <h4 className={styles.footerHeading}>Contact Us</h4>
              <div className={styles.contactInfo}>
                <p>24/7 Emergency Hotline</p>
                <p className={styles.phoneNumber}>+1 (555) 123-PETS</p>
                <p>info@pawsandclaws.com</p>
              </div>
            </div>
          </div>
          <div className={styles.footerBottom}>
            <p>&copy; 2025 Paws and Claws. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  )
}
