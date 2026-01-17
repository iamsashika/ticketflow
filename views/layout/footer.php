    <!-- Footer -->
    <footer class="footer">
        <div class="container container-footer">
            <div class="footer-col">
                <h3>About TicketFlow</h3>
                <p>We are dedicated to providing the best event ticketing experience. Discover, book, and enjoy events with ease.</p>
                <p>&copy; <?php echo date('Y'); ?> TicketFlow. All rights reserved.</p>
            </div>
            
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="/Event/home/about">About Us</a></li>
                    <li><a href="/Event/home/functionality">Functionality</a></li>
                    <li><a href="/Event/home/help">Help & Support</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Contact</h3>
                <ul class="footer-links">
                    <li><a href="#">support@ticketflow.lk</a></li>
                    <li><a href="#">+94 11 234 5678</a></li>
                </ul>
            </div>
        </div>
    </footer>
    </footer>

    <style>
        .footer {
            background-color: #1a1a1a;
            color: #ecf0f1;
            padding: 3rem 0;
            margin-top: auto;
        }

        .container-footer {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer h3 {
            color: var(--primary-color);
            margin-bottom: 1.2rem;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .footer p {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #bdc3c7;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }
    </style>

    <!-- Main JavaScript -->
    <script src="/Event/public/js/main.js"></script>

    </body>

    </html>